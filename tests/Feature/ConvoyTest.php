<?php

namespace Tests\Feature;

use App\Models\Convoy;
use App\Models\Event;
use App\Models\TravelRoute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConvoyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function routePayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Coast Run',
            'description' => 'Scenic.',
            'waypoints' => json_encode([
                ['lat' => 51.5074, 'lng' => -0.1278, 'label' => 'Start'],
                ['lat' => 48.8566, 'lng' => 2.3522, 'label' => ''],
            ]),
        ], $overrides);
    }

    public function test_distance_is_measured_along_the_waypoints(): void
    {
        $distance = TravelRoute::distanceFor([
            ['lat' => 51.5074, 'lng' => -0.1278],
            ['lat' => 48.8566, 'lng' => 2.3522],
        ]);

        $this->assertEqualsWithDelta(343.5, $distance, 3);
        $this->assertSame(0.0, TravelRoute::distanceFor([['lat' => 1, 'lng' => 1]]));
    }

    public function test_member_can_save_a_route_drawn_on_the_map(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('routes.store'), $this->routePayload())
            ->assertRedirect(route('routes.show', 'coast-run'));

        $route = TravelRoute::firstOrFail();
        $this->assertSame($user->id, $route->owner_id);
        $this->assertCount(2, $route->waypoints);
        $this->assertEqualsWithDelta(343.5, $route->distance_km, 3);

        $this->get(route('routes.show', $route))->assertOk()->assertSee('data-map', false)->assertSee('Coast Run');
    }

    public function test_routes_need_two_valid_stops(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('routes.store'), $this->routePayload([
            'waypoints' => json_encode([['lat' => 10, 'lng' => 10]]),
        ]))->assertSessionHasErrors('waypoints');

        $this->actingAs($user)->post(route('routes.store'), $this->routePayload([
            'waypoints' => json_encode([['lat' => 95, 'lng' => 10], ['lat' => 10, 'lng' => 200]]),
        ]))->assertSessionHasErrors(['waypoints.0.lat', 'waypoints.1.lng']);

        $this->actingAs($user)->post(route('routes.store'), $this->routePayload(['waypoints' => 'not json']))
            ->assertSessionHasErrors('waypoints');

        $this->assertDatabaseCount('travel_routes', 0);
    }

    public function test_guests_cannot_create_routes_or_convoys(): void
    {
        $this->get(route('routes.create'))->assertRedirect(route('login'));
        $this->get(route('convoys.create'))->assertRedirect(route('login'));
    }

    public function test_public_listings_and_pages_render(): void
    {
        $convoy = Convoy::factory()->create(['name' => 'Friday Convoy']);

        $this->get(route('routes.index'))->assertOk()->assertSee($convoy->travelRoute->name);
        $this->get(route('convoys.index'))->assertOk()->assertSee('Friday Convoy');
        $this->get(route('convoys.show', $convoy))->assertOk()->assertSee('data-map', false);
    }

    public function test_member_can_start_a_convoy_and_is_added_to_it(): void
    {
        $user = User::factory()->create();
        $route = TravelRoute::factory()->create();
        $event = Event::factory()->create();

        $this->actingAs($user)->post(route('convoys.store'), [
            'name' => 'Sunday Drive',
            'travel_route_id' => $route->id,
            'event_id' => $event->id,
            'departs_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'capacity' => 6,
        ])->assertRedirect(route('convoys.show', 'sunday-drive'));

        $convoy = Convoy::firstOrFail();
        $this->assertSame($user->id, $convoy->host_id);
        $this->assertTrue($convoy->members()->whereKey($user->id)->exists());

        $this->get(route('events.show', $event))->assertSee('Sunday Drive');
    }

    public function test_convoy_requires_a_future_departure_and_real_route(): void
    {
        $this->actingAs(User::factory()->create())->post(route('convoys.store'), [
            'name' => 'Bad',
            'travel_route_id' => 999,
            'departs_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'capacity' => 1,
        ])->assertSessionHasErrors(['travel_route_id', 'departs_at', 'capacity']);
    }

    public function test_member_can_join_and_leave_a_convoy(): void
    {
        $convoy = Convoy::factory()->create(['capacity' => 5]);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('convoys.join', $convoy));
        $this->assertTrue($convoy->members()->whereKey($user->id)->exists());

        $this->actingAs($user)->delete(route('convoys.leave', $convoy));
        $this->assertFalse($convoy->members()->whereKey($user->id)->exists());
    }

    public function test_joining_twice_adds_one_seat(): void
    {
        $convoy = Convoy::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('convoys.join', $convoy));
        $this->actingAs($user)->post(route('convoys.join', $convoy));

        $this->assertSame(2, $convoy->members()->count());
    }

    public function test_full_convoys_reject_new_members(): void
    {
        $convoy = Convoy::factory()->create(['capacity' => 1]);

        $this->actingAs(User::factory()->create())
            ->post(route('convoys.join', $convoy))
            ->assertSessionHasErrors('membership');

        $this->assertSame(1, $convoy->members()->count());
    }

    public function test_departed_convoys_reject_new_members(): void
    {
        $convoy = Convoy::factory()->create(['departs_at' => now()->subHour()]);

        $this->actingAs(User::factory()->create())
            ->post(route('convoys.join', $convoy))
            ->assertSessionHasErrors('membership');
    }

    public function test_host_cannot_leave_their_own_convoy(): void
    {
        $convoy = Convoy::factory()->create();

        $this->actingAs($convoy->host)
            ->delete(route('convoys.leave', $convoy))
            ->assertSessionHasErrors('membership');

        $this->assertTrue($convoy->members()->whereKey($convoy->host_id)->exists());
    }

    public function test_member_list_is_hidden_from_non_members(): void
    {
        $convoy = Convoy::factory()->create();
        $member = User::factory()->create(['name' => 'Zed Driver']);
        $convoy->members()->attach($member->id);

        $this->actingAs(User::factory()->create())->get(route('convoys.show', $convoy))->assertDontSee('Zed Driver');
        $this->actingAs($member)->get(route('convoys.show', $convoy))->assertSee('Zed Driver');
    }
}
