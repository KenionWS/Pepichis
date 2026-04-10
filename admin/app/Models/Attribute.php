<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    public const SCOPE_PRODUCER = 'producer';
    public const SCOPE_WINE = 'wine';
    public const SCOPE_BOTH = 'both';

    protected $fillable = [
        'name',
        'scope',
    ];

    public static function scopeOptions(): array
    {
        return [
            self::SCOPE_PRODUCER => 'Solo productores',
            self::SCOPE_WINE => 'Solo vinos',
            self::SCOPE_BOTH => 'Productores y vinos',
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('value');
    }
}
