<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\ConvoyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'notes', 'departs_at', 'capacity', 'travel_route_id', 'event_id'])]
class Convoy extends Model
{
    /** @use HasFactory<ConvoyFactory> */
    use HasFactory, HasSlug;

    public function slugSourceColumn(): string
    {
        return 'name';
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departs_at' => 'datetime',
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
     * @return BelongsTo<TravelRoute, $this>
     */
    public function travelRoute(): BelongsTo
    {
        return $this->belongsTo(TravelRoute::class, 'travel_route_id');
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'convoy_members')->withTimestamps();
    }

    /**
     * @param  Builder<Convoy>  $query
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('departs_at', '>=', now())->orderBy('departs_at');
    }

    public function spotsRemaining(): int
    {
        $taken = $this->members_count ?? $this->members()->count();

        return max(0, $this->capacity - $taken);
    }

    public function isFull(): bool
    {
        return $this->spotsRemaining() === 0;
    }
}
