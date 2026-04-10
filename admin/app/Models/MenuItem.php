<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public const TYPE_HOME_SECTION = 'home_section';
    public const TYPE_ROUTE = 'route';
    public const TYPE_EXTERNAL_URL = 'external_url';

    protected $fillable = [
        'label',
        'item_type',
        'item_value',
        'open_in_new_tab',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function typeOptions(): array
    {
        return [
            self::TYPE_HOME_SECTION => 'Seccion del home',
            self::TYPE_ROUTE => 'Ruta interna',
            self::TYPE_EXTERNAL_URL => 'URL externa',
        ];
    }

    public static function homeSectionOptions(): array
    {
        return [
            'nosotros' => 'Nosotros',
            'seleccion' => 'Seleccion',
            'productores' => 'Productores',
            'contacto' => 'Contacto',
        ];
    }

    public static function routeOptions(): array
    {
        return [
            'front.home' => 'Home',
            'front.producers.index' => 'Listado de productores',
            'front.notes.index' => 'Notas',
        ];
    }
}
