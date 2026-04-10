<?php

$db = new PDO('sqlite:C:/xampp8.2/htdocs/Pepichis/admin/database/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->beginTransaction();

$producers = [
    'Phelan Farm' => [
        'city' => 'Cambria',
        'state' => 'San Luis Obispo Coast, California',
        'country' => 'EE. UU.',
        'short' => 'Proyecto de clima frío influenciado por el Pacífico, liderado por Rajat Parr, con foco en equilibrio, energía y claridad.',
        'long' => [
            'Phelan Farm está ubicada en Cambria, en la costa de San Luis Obispo, California, en un entorno profundamente marcado por la influencia directa del Océano Pacífico. En este extremo del continente, el clima fresco, los vientos marinos y la cercanía al océano definen tanto el carácter del viñedo como la filosofía del proyecto.',
            'El trabajo se apoya en una agricultura cuidadosa y respetuosa del entorno, junto con una vinificación simple y precisa, orientada a expresar la diversidad de suelos y microclimas. El proyecto vitivinícola está liderado por Rajat Parr, uno de los sommeliers más influyentes de las últimas décadas, quien traslada al viñedo una mirada formada en la alta gastronomía, priorizando equilibrio, energía y claridad por sobre la potencia o la intervención.',
        ],
    ],
    'Nicolas Joly' => [
        'city' => 'Savennières',
        'state' => 'Loire',
        'country' => 'Francia',
        'short' => 'Figura central de la biodinámica, hoy continuada por Virginie Joly, con foco absoluto en la expresión del lugar.',
        'long' => [
            'Nicolas Joly es una figura central en la viticultura biodinámica a nivel mundial. Desde finales de los años setenta desarrolla su trabajo en Savennières, en el Valle del Loire, aplicando estos principios de manera integral en el viñedo y en la bodega. Su parcela más emblemática, Coulée de Serrant, es una de las pocas apelaciones monopole de Francia.',
            'En la actualidad, el proyecto continúa bajo la dirección de su hija, Virginie Joly, quien mantiene la filosofía biodinámica y el enfoque en la expresión del lugar, asegurando la continuidad del estilo y la identidad de los vinos.',
        ],
    ],
    'Domaine aux Moines' => [
        'city' => 'Savennières',
        'state' => 'Loire',
        'country' => 'Francia',
        'short' => 'Dominio histórico de Roche-aux-Moines, dirigido por Tessa Laroche, con Chenin Blanc de lectura tensa, clara y precisa del origen.',
        'long' => [
            'Domaine aux Moines es una propiedad histórica ubicada en Roche-aux-Moines, dentro de la denominación Savennières, en el Valle del Loire. El dominio se especializa exclusivamente en Chenin Blanc, elaborado a partir de parcelas con suelos de esquistos y gran complejidad geológica, característicos de esta zona del Loire.',
            'Actualmente está dirigido por Tessa Laroche, quien continúa el trabajo del dominio con una lectura precisa del viñedo y una interpretación clara del carácter de la zona. El enfoque está puesto en el respeto por el origen y en una vinificación que privilegia la tensión, la claridad y la identidad del lugar.',
        ],
    ],
    'Chiara Condello' => [
        'city' => 'Predappio',
        'state' => 'Emilia-Romagna',
        'country' => 'Italia',
        'short' => 'Sangiovese de suelos de Spungone, con agricultura orgánica y una vinificación enfocada en preservar la identidad del lugar.',
        'long' => [
            'Chiara Condello trabaja en Predappio, una zona montañosa de Emilia-Romagna caracterizada por sus suelos de Spungone, una formación calcárea de origen marino. En este contexto el Sangiovese adquiere un perfil tenso, preciso y de marcada identidad territorial.',
            'El proyecto se basa en agricultura orgánica y vinificación cuidada, con el objetivo de preservar la expresión del viñedo y reflejar con claridad el carácter del lugar.',
        ],
    ],
    'Wasenhaus' => [
        'city' => 'Baden',
        'state' => null,
        'country' => 'Alemania',
        'short' => 'Proyecto de Alexander Götze y Christoph Wolber en Baden, con mínima intervención y una lectura precisa de parcelas calcáreas.',
        'long' => [
            'Wasenhaus es un proyecto ubicado en Baden, Alemania, fundado por los enólogos Alexander Götze y Christoph Wolber. Ambos se formaron y trabajaron en Borgoña, experiencia que marcó profundamente su enfoque: trabajo parcelario, viñas antiguas y una interpretación precisa del lugar.',
            'En Baden elaboran Spätburgunder (Pinot Noir), Chardonnay y Weissburgunder (Pinot Blanc) a partir de parcelas específicas y suelos predominantemente calcáreos. La vinificación se basa en mínima intervención, buscando frescura, definición y una lectura clara del origen.',
        ],
    ],
];

$wines = [
    23 => ['name' => 'Brij Oso Rouge', 'slug' => 'brij-oso-rouge-2023', 'short' => 'Tinto de perfil fresco y fluido, con fruta roja, especias suaves y una textura ligera. Expresivo y versátil, pensado para acompañar la mesa.'],
    24 => ['name' => 'Brij Oso Chardonnay', 'slug' => 'brij-oso-chardonnay-2023', 'short' => 'Chardonnay de fermentación espontánea y crianza en recipientes neutros. Tensión marcada, textura envolvente y un final limpio.'],
    25 => ['name' => 'Phelan Farm Pinot Noir', 'slug' => 'phelan-farm-pinot-noir-2024', 'short' => 'Pinot Noir de clima frío, con fruta roja fresca, acidez definida y un perfil ágil, claramente influenciado por la cercanía del océano.'],
    26 => ['name' => 'Phelan Farm Chardonnay', 'slug' => 'phelan-farm-chardonnay-2023', 'short' => 'Chardonnay de viñedos propios, equilibrado y expresivo, con buena energía y un carácter netamente gastronómico.'],
    27 => ['name' => 'Predappio', 'slug' => 'predappio-2022', 'short' => 'Sangiovese fresco y preciso, con notas florales, fruta roja y taninos finos. Ágil y muy adecuado para la mesa.'],
    28 => ['name' => 'Le Lucciole', 'slug' => 'le-lucciole-2021', 'short' => 'Sangiovese de viñedos seleccionados, con mayor complejidad y una estructura equilibrada, profundo y armónico.'],
    29 => ['name' => 'Lo Starlisco', 'slug' => 'lo-starlisco-2020', 'short' => 'Expresión más concentrada del Sangiovese de la bodega. Persistente, con capas y una marcada personalidad.'],
    30 => ['name' => 'Predappio Magnum', 'slug' => 'predappio-2022-magnum', 'short' => 'Sangiovese fresco y preciso, con notas florales, fruta roja y taninos finos. Ágil y muy adecuado para la mesa.'],
    31 => ['name' => 'Le Lucciole Magnum', 'slug' => 'le-lucciole-2021-magnum', 'short' => 'Sangiovese de viñedos seleccionados, con mayor complejidad y una estructura equilibrada, profundo y armónico.'],
    32 => ['name' => 'Coulée de Serrant', 'slug' => 'coulee-de-serrant-2023', 'short' => 'Chenin Blanc proveniente de una única parcela histórica. Amplio e intenso en boca, combina concentración, energía y una gran capacidad de evolución en botella.'],
    33 => ['name' => 'Les Vieux Clos', 'slug' => 'les-vieux-clos-2023', 'short' => 'Chenin Blanc de viñedos antiguos, con un perfil más abierto en su juventud. Fruta madura, notas salinas y una textura amplia que se integra muy bien con la gastronomía.'],
    34 => ['name' => 'Roche aux Moines', 'slug' => 'roche-aux-moines-2023', 'short' => 'Chenin Blanc de perfil vertical, con acidez firme, textura precisa y un final largo y expresivo. Un vino serio, pensado para la mesa y con gran afinidad gastronómica.'],
    35 => ['name' => 'Filzen Chardonnay', 'slug' => 'filzen-chardonnay-2023', 'short' => 'Chardonnay con buen balance entre fruta, acidez y textura, de estilo preciso.'],
    36 => ['name' => 'Grand Ordinarie Spätburgunder', 'slug' => 'grand-ordinarie-spatburgunder-2024', 'short' => 'Pinot Noir fresco y ágil, con fruta roja vibrante, buena acidez y un perfil directo.'],
    37 => ['name' => 'Spätburgunder Landwein', 'slug' => 'spatburgunder-landwein-2023', 'short' => 'Pinot Noir equilibrado y expresivo, de estilo accesible y muy versátil para la mesa.'],
    38 => ['name' => 'Hohlen Chardonnay', 'slug' => 'hohlen-chardonnay-2023', 'short' => 'Chardonnay de parcela, con mayor complejidad y desarrollo en boca.'],
    39 => ['name' => 'Möhlin Spätburgunder', 'slug' => 'mohlin-spatburgunder-2023', 'short' => 'Pinot Noir de viñas antiguas, con complejidad aromática y textura refinada.'],
    40 => ['name' => 'Weissburgunder Landwein', 'slug' => 'weissburgunder-landwein-2023', 'short' => 'Pinot Blanc fresco y equilibrado, de perfil limpio y gastronómico.'],
    41 => ['name' => 'Kalk Spätburgunder', 'slug' => 'kalk-spatburgunder-2023', 'short' => 'Pinot Noir de suelos calcáreos, con mayor tensión y presencia en boca.'],
    42 => ['name' => 'Bellen Spätburgunder', 'slug' => 'bellen-spatburgunder-2023', 'short' => 'Vino de parcela destacada, definido y preciso, con gran claridad.'],
    43 => ['name' => 'Möhlin Weissburgunder', 'slug' => 'mohlin-weissburgunder-2023', 'short' => 'Pinot Blanc de viñas antiguas, elegante, profundo y con gran pureza.'],
    44 => ['name' => 'Hohlen Spätburgunder', 'slug' => 'hohlen-spatburgunder-2023', 'short' => 'Parcela única con carácter distintivo, marcada identidad del suelo y final persistente.'],
];

$producerStmt = $db->prepare('UPDATE producers SET city = :city, state = :state, country = :country, short_description = :short, long_description = :long WHERE name = :name');
foreach ($producers as $name => $data) {
    $long = '<p>' . implode('</p><p>', $data['long']) . '</p>';
    $producerStmt->execute([
        ':city' => $data['city'],
        ':state' => $data['state'],
        ':country' => $data['country'],
        ':short' => $data['short'],
        ':long' => $long,
        ':name' => $name,
    ]);
}

$wineStmt = $db->prepare('UPDATE wines SET name = :name, slug = :slug, short_description = :short, long_description = :long WHERE id = :id');
foreach ($wines as $id => $data) {
    $wineStmt->execute([
        ':id' => $id,
        ':name' => $data['name'],
        ':slug' => $data['slug'],
        ':short' => $data['short'],
        ':long' => $data['short'],
    ]);
}

$db->commit();
echo "encoding fixed\n";
