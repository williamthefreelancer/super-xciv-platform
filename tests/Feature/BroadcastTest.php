<?php

namespace Tests\Feature;

use App\Models\Broadcast;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_groups_broadcasts_by_status(): void
    {
        Broadcast::factory()->live()->create(['title' => 'On Air Now']);
        Broadcast::factory()->create(['title' => 'Tomorrow Show']);
        Broadcast::factory()->ended()->create(['title' => 'Finished Show']);

        $this->get(route('broadcasts.index'))
            ->assertOk()
            ->assertSee('On Air Now')
            ->assertSee('Tomorrow Show')
            ->assertSee('Finished Show');
    }

    public function test_member_can_schedule_a_broadcast(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->for($user, 'host')->create();

        $this->actingAs($user)->post(route('broadcasts.store'), [
            'title' => 'Launch Webcast',
            'type' => 'video',
            'stream_url' => 'https://example.com/live.m3u8',
            'event_id' => $event->id,
        ])->assertRedirect(route('broadcasts.show', 'launch-webcast'));

        $broadcast = Broadcast::firstOrFail();
        $this->assertSame($user->id, $broadcast->host_id);
        $this->assertSame(Broadcast::STATUS_SCHEDULED, $broadcast->status);
    }

    public function test_broadcast_validation_rejects_bad_input(): void
    {
        $someoneElsesEvent = Event::factory()->create();

        $this->actingAs(User::factory()->create())->post(route('broadcasts.store'), [
            'title' => 'Bad',
            'type' => 'hologram',
            'stream_url' => 'javascript:alert(1)',
            'event_id' => $someoneElsesEvent->id,
        ])->assertSessionHasErrors(['type', 'stream_url', 'event_id']);
    }

    public function test_host_can_go_live_and_end(): void
    {
        $broadcast = Broadcast::factory()->create(['stream_url' => null]);

        $this->actingAs($broadcast->host)->post(route('broadcasts.live', $broadcast), [
            'stream_url' => 'https://example.com/hls/stream.m3u8',
        ]);

        $broadcast->refresh();
        $this->assertTrue($broadcast->isLive());
        $this->assertNotNull($broadcast->started_at);
        $this->assertSame('https://example.com/hls/stream.m3u8', $broadcast->stream_url);

        $this->actingAs($broadcast->host)->post(route('broadcasts.end', $broadcast));

        $this->assertTrue($broadcast->refresh()->isEnded());
        $this->assertNotNull($broadcast->ended_at);
    }

    public function test_only_the_host_can_control_a_broadcast(): void
    {
        $broadcast = Broadcast::factory()->create();
        $stranger = User::factory()->create();

        $this->post(route('broadcasts.live', $broadcast))->assertRedirect(route('login'));
        $this->actingAs($stranger)->post(route('broadcasts.live', $broadcast))->assertForbidden();
        $this->actingAs($stranger)->post(route('broadcasts.end', $broadcast))->assertForbidden();

        $this->assertSame(Broadcast::STATUS_SCHEDULED, $broadcast->refresh()->status);
    }

    public function test_ended_broadcasts_cannot_go_live_again(): void
    {
        $broadcast = Broadcast::factory()->ended()->create();

        $this->actingAs($broadcast->host)
            ->post(route('broadcasts.live', $broadcast))
            ->assertSessionHasErrors('broadcast');

        $this->assertTrue($broadcast->refresh()->isEnded());
    }

    public function test_status_endpoint_reports_current_status(): void
    {
        $broadcast = Broadcast::factory()->live()->create();

        $this->getJson(route('broadcasts.status', $broadcast))->assertOk()->assertExactJson(['status' => 'live']);
    }

    public function test_live_pages_render_the_right_player(): void
    {
        $hls = Broadcast::factory()->live()->create(['stream_url' => 'https://cdn.example.com/a.m3u8']);
        $audio = Broadcast::factory()->audio()->live()->create(['stream_url' => 'https://cdn.example.com/radio.mp3']);
        $youtube = Broadcast::factory()->live()->create(['stream_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);

        $this->get(route('broadcasts.show', $hls))->assertSee('data-hls="https://cdn.example.com/a.m3u8"', false);
        $this->get(route('broadcasts.show', $audio))->assertSee('<audio controls autoplay src="https://cdn.example.com/radio.mp3">', false);
        $this->get(route('broadcasts.show', $youtube))->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false);
    }

    public function test_scheduled_and_ended_pages_show_placeholders_instead_of_players(): void
    {
        $scheduled = Broadcast::factory()->create();
        $ended = Broadcast::factory()->ended()->create();

        $this->get(route('broadcasts.show', $scheduled))->assertSee('updates automatically')->assertDontSee('data-hls');
        $this->get(route('broadcasts.show', $ended))->assertSee('This broadcast ended')->assertDontSee('data-hls');
    }

    public function test_playback_detection(): void
    {
        $make = fn (string $url, string $type = 'video') => (new Broadcast(['stream_url' => $url, 'type' => $type]))->playback();

        $this->assertSame('embed', $make('https://youtu.be/dQw4w9WgXcQ')['kind']);
        $this->assertSame('https://player.vimeo.com/video/12345', $make('https://vimeo.com/12345')['src']);
        $this->assertSame('hls', $make('https://cdn.example.com/live/index.m3u8')['kind']);
        $this->assertSame('audio', $make('https://cdn.example.com/radio', 'audio')['kind']);
        $this->assertNull($make('https://www.youtube.com/watch?v=bad'));
        $this->assertNull((new Broadcast)->playback());
    }
}
