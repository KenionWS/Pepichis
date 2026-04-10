<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function producers(): BelongsToMany
    {
        return $this->belongsToMany(Producer::class, 'producer_attribute_value')
            ->withTimestamps();
    }

    public function wines(): BelongsToMany
    {
        return $this->belongsToMany(Wine::class, 'wine_attribute_value')
            ->withTimestamps();
    }
}
