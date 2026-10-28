<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Generates a unique `slug` on create and routes by it.
 *
 * @mixin Model
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model): void {
            $model->slug ??= static::uniqueSlugFor((string) $model->{$model->slugSourceColumn()});
        });
    }

    public function slugSourceColumn(): string
    {
        return 'title';
    }

    protected static function uniqueSlugFor(string $source): string
    {
        $base = Str::slug($source) ?: 'item';
        $slug = $base;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(5));
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
