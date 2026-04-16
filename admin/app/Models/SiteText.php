<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteText extends Model
{
    use HasFactory;

    public const KEY_ABOUT = 'about';

    protected $fillable = [
        'key',
        'title',
        'eyebrow',
        'body',
    ];

    public static function defaultAbout(): array
    {
        return [
            'key' => self::KEY_ABOUT,
            'title' => 'Nosotros',
            'eyebrow' => 'Quienes somos',
            'body' => implode("\n\n", [
                'No empezamos importando vinos. Empezamos tomandolos.',
                'Tomandolos en mesas largas, en barras angostas, en cocinas que ya estaban cerrando. En restaurantes donde la carta parecia escrita por alguien con curiosidad y con criterio. Vinos que no buscaban impresionar, pero que siempre tenian algo para decir.',
                'Durante anos fuimos consumidores atentos. Aprendimos que detras de una gran botella suele haber una mirada clara y convicciones firmes. Como una mesa de madera apenas trabajada: sus marcas, sus vetas y su textura no son imperfecciones, son justamente lo que la vuelve unica. Sus perfecciones.',
                'Muchos de los vinos que nos marcaron nacen de ese mismo lugar. Proyectos pequenos, personales, impulsados por personas profundamente comprometidas con lo que hacen. Vinos producidos en cantidades limitadas, dificiles de repetir, que siguen una logica propia y no buscan parecerse a otros.',
                'Esta importadora nace de ese recorrido. De la idea de acercar a la gastronomia argentina vinos de productores que admiramos, ligados a un lugar, a una anada y a una forma de entender el vino como una expresion honesta.',
                'Creemos en relaciones a largo plazo, en asignaciones cuidadas y en botellas que encuentran su verdadero sentido cuando llegan a la mesa correcta.',
                'Importamos los vinos que nos gusta tomar. Los que pediramos si estuvieramos del otro lado de la carta.',
            ]),
        ];
    }
}
