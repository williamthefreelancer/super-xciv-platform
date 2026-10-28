<?php

namespace App\Services;

class LiveKitTokenService
{
    public function __construct(
        protected ?string $apiKey = null,
        protected ?string $apiSecret = null,
        protected ?string $serverUrl = null,
    ) {
        $this->apiKey ??= (string) config('services.livekit.api_key');
        $this->apiSecret ??= (string) config('services.livekit.api_secret');
        $this->serverUrl ??= (string) config('services.livekit.url');
    }

    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    /**
     * @param  array{
     *     room: string,
     *     identity: string,
     *     name?: string,
     *     canPublish?: bool,
     *     canSubscribe?: bool,
     *     canPublishData?: bool,
     *     ttlSeconds?: int,
     *     metadata?: string,
     * }  $options
     */
    public function createToken(array $options): string
    {
        $now = time();
        $ttl = $options['ttlSeconds'] ?? 3600 * 6;

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $videoGrant = [
            'room' => $options['room'],
            'roomJoin' => true,
            'canPublish' => $options['canPublish'] ?? false,
            'canSubscribe' => $options['canSubscribe'] ?? true,
            'canPublishData' => $options['canPublishData'] ?? true,
        ];

        if (! empty($options['canPublish'])) {
            $videoGrant['roomAdmin'] = true;
        }

        $payload = [
            'exp' => $now + $ttl,
            'nbf' => $now,
            'iat' => $now,
            'iss' => $this->apiKey,
            'sub' => $options['identity'],
            'name' => $options['name'] ?? $options['identity'],
            'video' => $videoGrant,
        ];

        if (isset($options['metadata'])) {
            $payload['metadata'] = $options['metadata'];
        }

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        $signature = hash_hmac('sha256', "{$encodedHeader}.{$encodedPayload}", $this->apiSecret, true);
        $encodedSignature = $this->base64UrlEncode($signature);

        return "{$encodedHeader}.{$encodedPayload}.{$encodedSignature}";
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
