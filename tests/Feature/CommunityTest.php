<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\CommunityPost;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_and_searches_communities(): void
    {
        Community::factory()->create(['name' => 'Rooftop Hosts']);
        Community::factory()->create(['name' => 'Cellar Crew']);

        $this->get(route('communities.index'))->assertOk()->assertSee('Rooftop Hosts')->assertSee('Cellar Crew');

        $this->get(route('communities.index', ['q' => 'Rooftop']))
            ->assertSee('Rooftop Hosts')
            ->assertDontSee('Cellar Crew');
    }

    public function test_member_can_start_a_community_and_becomes_a_member(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('communities.store'), [
            'name' => 'Door Staff Network',
            'summary' => 'Security and door teams.',
        ])->assertRedirect(route('communities.show', 'door-staff-network'));

        $community = Community::firstOrFail();
        $this->assertSame($user->id, $community->owner_id);
        $this->assertTrue($community->members()->whereKey($user->id)->exists());
    }

    public function test_guests_cannot_start_a_community(): void
    {
        $this->post(route('communities.store'), [])->assertRedirect(route('login'));
    }

    public function test_user_can_join_and_leave_but_owner_cannot_leave(): void
    {
        $community = Community::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('communities.join', $community));
        $this->assertTrue($community->members()->whereKey($user->id)->exists());

        $this->actingAs($user)->delete(route('communities.leave', $community));
        $this->assertFalse($community->members()->whereKey($user->id)->exists());

        $this->actingAs($community->owner)
            ->delete(route('communities.leave', $community))
            ->assertSessionHasErrors('membership');
        $this->assertTrue($community->members()->whereKey($community->owner_id)->exists());
    }

    public function test_only_members_can_post(): void
    {
        $community = Community::factory()->create();
        $outsider = User::factory()->create();
        $member = User::factory()->create();
        $community->members()->attach($member->id);

        $this->actingAs($outsider)->post(route('communities.posts.store', $community), ['body' => 'Hi'])->assertForbidden();

        $this->actingAs($member)->post(route('communities.posts.store', $community), ['body' => 'Hello everyone'])
            ->assertSessionHas('status');

        $this->get(route('communities.show', $community))->assertSee('Hello everyone');
    }

    public function test_posts_can_be_removed_by_author_or_owner_only(): void
    {
        $community = Community::factory()->create();
        $author = User::factory()->create();
        $stranger = User::factory()->create();
        $post = CommunityPost::factory()->for($community)->for($author, 'author')->create();

        $this->actingAs($stranger)->delete(route('communities.posts.destroy', [$community, $post]))->assertForbidden();

        $this->actingAs($community->owner)->delete(route('communities.posts.destroy', [$community, $post]));
        $this->assertModelMissing($post);
    }

    public function test_member_list_is_hidden_from_non_members(): void
    {
        $community = Community::factory()->create();
        $member = User::factory()->create(['name' => 'Zed Member']);
        $community->members()->attach($member->id);

        $this->actingAs(User::factory()->create())
            ->get(route('communities.show', $community))
            ->assertDontSee('Zed Member');

        $this->actingAs($member)
            ->get(route('communities.show', $community))
            ->assertSee('Zed Member');
    }

    public function test_events_can_only_be_linked_to_communities_the_host_belongs_to(): void
    {
        $user = User::factory()->create();
        $mine = Community::factory()->create();
        $mine->members()->attach($user->id);
        $other = Community::factory()->create();

        $payload = [
            'title' => 'Community Supper',
            'summary' => 'Dinner.',
            'venue' => 'The Annex',
            'city' => 'Austin',
            'starts_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'capacity' => 10,
        ];

        $this->actingAs($user)->post(route('events.store'), $payload + ['community_id' => $other->id])
            ->assertSessionHasErrors('community_id');

        $this->actingAs($user)->post(route('events.store'), $payload + ['community_id' => $mine->id]);
        $this->assertSame($mine->id, Event::firstOrFail()->community_id);
        $this->get(route('communities.show', $mine))->assertSee('Community Supper');
    }
}
