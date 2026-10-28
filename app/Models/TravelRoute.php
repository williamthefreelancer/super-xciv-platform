<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\TravelRouteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'waypoints', 'distance_km'])]
class TravelRoute extends Model
{
    /** @use HasFactory<TravelRouteFactory> */
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
            'waypoints' => 'array',
            'distance_km' => 'float',
        ];
    }

    /**
     * Great-circle length of the path through the waypoints, in kilometres.
     *
     * @param  list<array{lat: float|int|string, lng: float|int|string}>  $waypoints
     */
    public static function distanceFor(array $waypoints): float
    {
        $total = 0.0;

        for ($i = 1; $i < count($waypoints); $i++) {
            $lat1 = deg2rad((float) $waypoints[$i - 1]['lat']);
            $lat2 = deg2rad((float) $waypoints[$i]['lat']);
            $dLat = $lat2 - $lat1;
            $dLng = deg2rad((float) $waypoints[$i]['lng'] - (float) $waypoints[$i - 1]['lng']);

            $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;
            $total += 6371 * 2 * asin(min(1, sqrt($a)));
        }

        return round($total, 2);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return HasMany<Convoy, $this>
     */
    public function convoys(): HasMany
    {
        return $this->hasMany(Convoy::class);
    }
}
