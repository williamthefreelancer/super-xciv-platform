<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\CommunityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'summary', 'description', 'city'])]
class Community extends Model
{
    /** @use HasFactory<CommunityFactory> */
    use HasFactory, HasSlug;

    public function slugSourceColumn(): string
    {
        return 'name';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_members')->withTimestamps();
    }

    /**
     * @return HasMany<CommunityPost, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(CommunityPost::class);
    }

    /**
     * @return HasMany<Event, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
