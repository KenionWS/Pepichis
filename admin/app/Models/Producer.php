<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'image_path',
        'city',
        'state',
        'country',
        'short_description',
        'long_description',
    ];

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'producer_attribute_value')
            ->withTimestamps();
    }

    public function wines(): HasMany
    {
        return $this->hasMany(Wine::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
