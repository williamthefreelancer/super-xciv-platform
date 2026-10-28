<?php

namespace Tests\Feature;

use App\Models\Broadcast;
use App\Models\User;
use App\Services\LiveKitTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveKitTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_service_generates_valid_signed_jwt(): void
    {
        $service = new LiveKitTokenService(
            apiKey: 'test-api-key',
            apiSecret: 'test-api-secret',
            serverUrl: 'ws://127.0.0.1:7880'
        );

        $token = $service->createToken([
            'room' => 'stage-room',
            'identity' => 'user-42',
            'name' => 'Alice',
            'canPublish' => true,
        ]);

        $parts = explode('.', $token);
        $this->assertCount(3, $parts);

        $header = json_decode(base64_decode(strtr($parts[0], '-_', '+/')), true);
        $this->assertSame('HS256', $header['alg']);
        $this->assertSame('JWT', $header['typ']);

        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        $this->assertSame('test-api-key', $payload['iss']);
        $this->assertSame('user-42', $payload['sub']);
        $this->assertSame('Alice', $payload['name']);
        $this->assertSame('stage-room', $payload['video']['room']);
        $this->assertTrue($payload['video']['canPublish']);
        $this->assertTrue($payload['video']['canSubscribe']);
        $this->assertTrue($payload['video']['roomAdmin']);

        // Verify HMAC-SHA256 signature
        $expectedSig = rtrim(strtr(base64_encode(hash_hmac('sha256', "{$parts[0]}.{$parts[1]}", 'test-api-secret', true)), '+/', '-_'), '=');
        $this->assertSame($expectedSig, $parts[2]);
    }

    public function test_guest_receives_subscribe_only_livekit_token(): void
    {
        $broadcast = Broadcast::factory()->live()->create();

        $response = $this->getJson(route('broadcasts.token', $broadcast));

        $response->assertOk()
            ->assertJsonStructure(['serverUrl', 'token', 'room', 'canPublish', 'identity', 'type'])
            ->assertJson([
                'canPublish' => false,
                'room' => "broadcast-{$broadcast->id}",
            ]);

        $this->assertStringStartsWith('guest_', $response->json('identity'));
    }

    public function test_authenticated_viewer_receives_subscribe_only_token(): void
    {
        $broadcast = Broadcast::factory()->live()->create();
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->getJson(route('broadcasts.token', $broadcast));

        $response->assertOk()
            ->assertJson([
                'canPublish' => false,
                'identity' => "user_{$viewer->id}",
                'name' => $viewer->name,
            ]);
    }

    public function test_host_receives_publishing_livekit_token(): void
    {
        $broadcast = Broadcast::factory()->live()->create();

        $response = $this->actingAs($broadcast->host)->getJson(route('broadcasts.token', $broadcast));

        $response->assertOk()
            ->assertJson([
                'canPublish' => true,
                'identity' => "user_{$broadcast->host_id}",
            ]);
    }

    public function test_host_cannot_publish_to_ended_broadcast(): void
    {
        $broadcast = Broadcast::factory()->ended()->create();

        $response = $this->actingAs($broadcast->host)->getJson(route('broadcasts.token', $broadcast));

        $response->assertOk()
            ->assertJson(['canPublish' => false]);
    }

    public function test_live_broadcast_renders_livekit_stage_when_no_external_stream(): void
    {
        $broadcast = Broadcast::factory()->live()->create(['stream_url' => null]);

        $this->get(route('broadcasts.show', $broadcast))
            ->assertOk()
            ->assertSee('data-livekit', false)
            ->assertSee('livekit-stage', false)
            ->assertSee('LiveKit WebRTC');
    }
}
