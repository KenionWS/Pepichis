<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'cover_image_path',
        'excerpt',
        'body',
        'seo_title',
        'seo_description',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $nestedQuery) {
                $nestedQuery
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
