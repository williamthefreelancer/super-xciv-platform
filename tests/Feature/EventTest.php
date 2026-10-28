<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_gatherings_index_lists_only_upcoming_events(): void
    {
        $upcoming = Event::factory()->create(['title' => 'Rooftop Supper']);
        $past = Event::factory()->past()->create(['title' => 'Old Soiree']);

        $this->get(route('events.index'))
            ->assertOk()
            ->assertSee($upcoming->title)
            ->assertDontSee($past->title);
    }

    public function test_event_listing_can_be_filtered_by_search_city_and_past(): void
    {
        Event::factory()->create(['title' => 'Harbour Supper', 'city' => 'Austin']);
        Event::factory()->create(['title' => 'Canal Brunch', 'city' => 'Dallas']);
        Event::factory()->past()->create(['title' => 'Old Soiree', 'city' => 'Austin']);

        $this->get(route('events.index', ['q' => 'Harbour']))
            ->assertSee('Harbour Supper')
            ->assertDontSee('Canal Brunch');

        $this->get(route('events.index', ['city' => 'Dallas']))
            ->assertSee('Canal Brunch')
            ->assertDontSee('Harbour Supper');

        $this->get(route('events.index', ['when' => 'past']))
            ->assertSee('Old Soiree')
            ->assertDontSee('Harbour Supper');
    }

    public function test_event_detail_page_renders(): void
    {
        $event = Event::factory()->create();

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee($event->title);
    }

    public function test_guests_cannot_create_events(): void
    {
        $this->get(route('events.create'))->assertRedirect(route('login'));
        $this->post(route('events.store'), [])->assertRedirect(route('login'));
    }

    public function test_member_can_host_an_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'title' => 'Industry Dinner',
            'summary' => 'A long table for operators.',
            'venue' => 'The Annex',
            'city' => 'Austin',
            'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'capacity' => 12,
        ]);

        $event = Event::firstOrFail();
        $response->assertRedirect(route('events.show', $event));
        $this->assertSame($user->id, $event->host_id);
        $this->assertSame('industry-dinner', $event->slug);
    }

    public function test_event_requires_valid_details(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('events.store'), [
                'title' => '',
                'starts_at' => now()->subDay()->format('Y-m-d H:i:s'),
                'capacity' => 1,
            ])
            ->assertSessionHasErrors(['title', 'summary', 'venue', 'city', 'starts_at', 'capacity']);
    }

    public function test_member_can_register_and_cancel(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 5]);

        $this->actingAs($user)->post(route('events.register', $event))->assertSessionHas('status');
        $this->assertDatabaseHas('event_registrations', ['event_id' => $event->id, 'user_id' => $user->id]);

        $this->actingAs($user)->delete(route('events.unregister', $event));
        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id, 'user_id' => $user->id]);
    }

    public function test_registering_twice_creates_one_registration(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $this->actingAs($user)->post(route('events.register', $event));
        $this->actingAs($user)->post(route('events.register', $event));

        $this->assertSame(1, $event->registrations()->count());
    }

    public function test_full_events_reject_new_registrations(): void
    {
        $event = Event::factory()->create(['capacity' => 2]);
        EventRegistration::factory()->count(2)->for($event)->create();

        $this->actingAs(User::factory()->create())
            ->post(route('events.register', $event))
            ->assertSessionHasErrors('registration');

        $this->assertSame(2, $event->registrations()->count());
    }

    public function test_past_events_reject_registrations(): void
    {
        $event = Event::factory()->past()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('events.register', $event))
            ->assertSessionHasErrors('registration');
    }

    public function test_only_the_host_sees_the_attendee_list(): void
    {
        $event = Event::factory()->create();
        $attendee = User::factory()->create(['name' => 'Zed Attendee']);
        EventRegistration::factory()->for($event)->for($attendee)->create();

        $this->actingAs(User::factory()->create())
            ->get(route('events.show', $event))
            ->assertDontSee('Zed Attendee');

        $this->actingAs($event->host)
            ->get(route('events.show', $event))
            ->assertSee('Zed Attendee');
    }
}
