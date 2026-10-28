<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'summary', 'description', 'venue', 'city', 'starts_at', 'capacity', 'community_id'])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory, HasSlug;

    /**
     * @return BelongsTo<Community, $this>
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * @return HasMany<Broadcast, $this>
     */
    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }

    /**
     * @return HasMany<Convoy, $this>
     */
    public function convoys(): HasMany
    {
        return $this->hasMany(Convoy::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
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
     * @return HasMany<EventRegistration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_registrations')->withTimestamps();
    }

    /**
     * @param  Builder<Event>  $query
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('starts_at', '>=', now())->orderBy('starts_at');
    }

    public function spotsRemaining(): int
    {
        $taken = $this->registrations_count ?? $this->registrations()->count();

        return max(0, $this->capacity - $taken);
    }

    public function isFull(): bool
    {
        return $this->spotsRemaining() === 0;
    }
}
