<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'organization_name', 'sector', 'city'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var array<string, string>
     */
    public const SECTORS = [
        'event_maker' => 'Event maker',
        'nightlife' => 'Nightlife & clubs',
        'hospitality' => 'Hospitality',
    ];

    /**
     * @return HasMany<Event, $this>
     */
    public function hostedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'host_id');
    }

    /**
     * @return BelongsToMany<Event, $this>
     */
    public function attendingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_registrations')->withTimestamps();
    }

    public function sectorLabel(): ?string
    {
        return self::SECTORS[$this->sector] ?? null;
    }

    /**
     * @return BelongsToMany<Community, $this>
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class, 'community_members')->withTimestamps();
    }

    /**
     * @return HasMany<Community, $this>
     */
    public function ownedCommunities(): HasMany
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    /**
     * @return HasMany<Broadcast, $this>
     */
    public function hostedBroadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class, 'host_id');
    }

    /**
     * @return HasMany<TravelRoute, $this>
     */
    public function travelRoutes(): HasMany
    {
        return $this->hasMany(TravelRoute::class, 'owner_id');
    }

    /**
     * @return HasMany<Convoy, $this>
     */
    public function hostedConvoys(): HasMany
    {
        return $this->hasMany(Convoy::class, 'host_id');
    }

    /**
     * @return BelongsToMany<Convoy, $this>
     */
    public function convoys(): BelongsToMany
    {
        return $this->belongsToMany(Convoy::class, 'convoy_members')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
