<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\BroadcastFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'description', 'type', 'stream_url', 'scheduled_at', 'event_id'])]
class Broadcast extends Model
{
    /** @use HasFactory<BroadcastFactory> */
    use HasFactory, HasSlug;

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_LIVE = 'live';

    public const STATUS_ENDED = 'ended';

    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'video' => 'Video webcast',
        'audio' => 'Audio broadcast',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @param  Builder<Broadcast>  $query
     */
    public function scopeLive(Builder $query): void
    {
        $query->where('status', self::STATUS_LIVE)->orderByDesc('started_at');
    }

    /**
     * @param  Builder<Broadcast>  $query
     */
    public function scopeScheduled(Builder $query): void
    {
        $query->where('status', self::STATUS_SCHEDULED)->orderBy('scheduled_at');
    }

    /**
     * @param  Builder<Broadcast>  $query
     */
    public function scopeEnded(Builder $query): void
    {
        $query->where('status', self::STATUS_ENDED)->orderByDesc('ended_at');
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isEnded(): bool
    {
        return $this->status === self::STATUS_ENDED;
    }

    /**
     * How the player should render the stream URL.
     *
     * @return array{kind: string, src: string}|null
     */
    public function playback(): ?array
    {
        if (! $this->stream_url) {
            return null;
        }

        $parts = parse_url($this->stream_url);
        $host = preg_replace('/^www\./', '', strtolower($parts['host'] ?? ''));
        $path = $parts['path'] ?? '';

        if (in_array($host, ['youtube.com', 'm.youtube.com', 'youtu.be'], true)) {
            parse_str($parts['query'] ?? '', $query);
            $id = $host === 'youtu.be'
                ? trim($path, '/')
                : ($query['v'] ?? basename($path));

            return preg_match('/^[\w-]{11}$/', (string) $id)
                ? ['kind' => 'embed', 'src' => 'https://www.youtube-nocookie.com/embed/'.$id]
                : null;
        }

        if ($host === 'vimeo.com' && preg_match('#/(\d+)#', $path, $matches)) {
            return ['kind' => 'embed', 'src' => 'https://player.vimeo.com/video/'.$matches[1]];
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'm3u8') {
            return ['kind' => 'hls', 'src' => $this->stream_url];
        }

        return ['kind' => $this->type === 'audio' ? 'audio' : 'video', 'src' => $this->stream_url];
    }
}
