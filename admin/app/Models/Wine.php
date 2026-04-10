<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wine extends Model
{
    use HasFactory;

    protected $fillable = [
        'producer_id',
        'name',
        'slug',
        'sort_order',
        'show_on_home',
        'image_path',
        'short_description',
        'long_description',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'wine_attribute_value')
            ->withTimestamps();
    }
}
