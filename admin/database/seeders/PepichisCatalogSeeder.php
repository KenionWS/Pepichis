<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Producer;
use App\Models\Wine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PepichisCatalogSeeder extends Seeder
{
    public function run()
    {
        DB::table('producer_attribute_value')->delete();
        DB::table('wine_attribute_value')->delete();
        Wine::query()->delete();
        Producer::query()->delete();
        AttributeValue::query()->delete();
        Attribute::query()->delete();

        $attributes = $this->createAttributes();
        $producers = $this->seedProducers($attributes);
        $this->seedWines($attributes, $producers);
    }

    private function createAttributes(): array
    {
        $definitions = [
            'Ciudad' => ['scope' => Attribute::SCOPE_PRODUCER, 'values' => ['Cambria', 'Predappio', 'Savennières', 'Baden']],
            'Estado' => ['scope' => Attribute::SCOPE_PRODUCER, 'values' => ['California', 'Emilia-Romagna', 'Loire', 'Baden']],
            'País' => ['scope' => Attribute::SCOPE_PRODUCER, 'values' => ['EEUU', 'Italia', 'Francia', 'Alemania']],
            'Región' => ['scope' => Attribute::SCOPE_PRODUCER, 'values' => ['San Luis Obispo Coast', 'Predappio', 'Savennières', 'Roche-aux-Moines', 'Baden']],
            'Tipo' => ['scope' => Attribute::SCOPE_WINE, 'values' => ['Blend', 'Chardonnay', 'Pinot Noir', 'Chenin Blanc', 'Spätburgunder', 'Weissburgunder']],
            'Año' => ['scope' => Attribute::SCOPE_WINE, 'values' => ['2020', '2021', '2022', '2023', '2024']],
            'Formato' => ['scope' => Attribute::SCOPE_WINE, 'values' => ['Botella', 'Magnum']],
        ];

        $map = [];

        foreach ($definitions as $name => $definition) {
            $attribute = Attribute::create([
                'name' => $name,
                'scope' => $definition['scope'],
            ]);

            foreach ($definition['values'] as $value) {
                $attribute->values()->create(['value' => $value]);
            }

            $map[$name] = $attribute->load('values');
        }

        return $map;
    }

    private function seedProducers(array $attributes): array
    {
        $items = [
            [
                'name' => 'Phelan Farm',
                'city' => 'Cambria',
                'state' => 'California',
                'country' => 'EEUU',
                'image' => 'productores/Rajat Parr Phelan Farm.webp',
                'short_description' => 'Viñedo marcado por la influencia directa del Pacífico. Liderado por Rajat Parr, el proyecto prioriza equilibrio, energía y claridad por sobre la potencia, con una agricultura cuidadosa y vinificación precisa.',
                'long_description' => 'Phelan Farm está ubicada en Cambria, en la costa de San Luis Obispo, California, en un entorno profundamente marcado por la influencia directa del Océano Pacífico. En este extremo del continente, el clima fresco, los vientos marinos y la cercanía al océano definen tanto el carácter del viñedo como la filosofía del proyecto.' . "\n\n" . 'El trabajo se apoya en una agricultura cuidadosa y respetuosa del entorno, junto con una vinificación simple y precisa, orientada a expresar la diversidad de suelos y microclimas. El proyecto vitivinícola está liderado por Rajat Parr, uno de los sommeliers más influyentes de Estados Unidos.',
                'attributes' => ['Ciudad' => 'Cambria', 'Estado' => 'California', 'País' => 'EEUU', 'Región' => 'San Luis Obispo Coast'],
            ],
            [
                'name' => 'Nicolas Joly',
                'city' => 'Savennières',
                'state' => 'Loire',
                'country' => 'Francia',
                'image' => 'productores/virginie y nicolas joly coulee de la serrant.jpg',
                'short_description' => 'Figura central de la biodinámica mundial. Su parcela Coulée de Serrant es una de las pocas apelaciones monopole de Francia. Hoy el proyecto continúa bajo la dirección de Virginie Joly.',
                'long_description' => 'Figura central de la biodinámica mundial. Su parcela Coulée de Serrant es una de las pocas apelaciones monopole de Francia. Hoy el proyecto continúa bajo la dirección de Virginie Joly.',
                'attributes' => ['Ciudad' => 'Savennières', 'Estado' => 'Loire', 'País' => 'Francia', 'Región' => 'Savennières'],
            ],
            [
                'name' => 'Domaine aux Moines',
                'city' => 'Savennières',
                'state' => 'Loire',
                'country' => 'Francia',
                'image' => 'productores/tessa laroche domaine aux moines.jpg',
                'short_description' => 'Propiedad histórica en Roche-aux-Moines, especializada en Chenin Blanc. Dirigido por Tessa Laroche, el enfoque privilegia la tensión, la claridad y la identidad del lugar.',
                'long_description' => 'Propiedad histórica en Roche-aux-Moines, especializada en Chenin Blanc. Dirigido por Tessa Laroche, el enfoque privilegia la tensión, la claridad y la identidad del lugar.',
                'attributes' => ['Ciudad' => 'Savennières', 'Estado' => 'Loire', 'País' => 'Francia', 'Región' => 'Roche-aux-Moines'],
            ],
            [
                'name' => 'Chiara Condello',
                'city' => 'Predappio',
                'state' => 'Emilia-Romagna',
                'country' => 'Italia',
                'image' => 'productores/Chiara Condello 1.jpg',
                'short_description' => 'Sangiovese de suelos de Spungone, formación calcárea de origen marino. Agricultura orgánica y vinificación cuidada para expresar con claridad el carácter del lugar.',
                'long_description' => 'Sangiovese de suelos de Spungone, una formación calcárea de origen marino que le da al vino una identidad singular. Chiara trabaja con agricultura orgánica y una vinificación cuidada, buscando expresar con claridad el carácter de Predappio.' . "\n\n" . 'Sus vinos capturan la esencia de un terruño único: la tensión mineral del Spungone, la frescura de la altitud y la expresión franca del Sangiovese sin artificios.',
                'attributes' => ['Ciudad' => 'Predappio', 'Estado' => 'Emilia-Romagna', 'País' => 'Italia', 'Región' => 'Predappio'],
            ],
            [
                'name' => 'Wasenhaus',
                'city' => 'Baden',
                'state' => 'Baden',
                'country' => 'Alemania',
                'image' => 'productores/wasenhaus alex.webp',
                'short_description' => 'Fundado por Alexander Götze y Christoph Wolber, formados en Borgoña. Elaboran Pinot Noir, Chardonnay y Pinot Blanc de suelos calcáreos con mínima intervención.',
                'long_description' => 'Fundado por Alexander Götze y Christoph Wolber, formados en Borgoña. Elaboran Pinot Noir, Chardonnay y Pinot Blanc de suelos calcáreos con mínima intervención.',
                'attributes' => ['Ciudad' => 'Baden', 'Estado' => 'Baden', 'País' => 'Alemania', 'Región' => 'Baden'],
            ],
        ];

        $created = [];

        foreach ($items as $item) {
            $position = count($created) + 1;

            $producer = Producer::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'sort_order' => $position,
                'image_path' => $this->importImage($item['image'], 'producers'),
                'city' => $item['city'],
                'state' => $item['state'],
                'country' => $item['country'],
                'short_description' => $item['short_description'],
                'long_description' => $item['long_description'],
            ]);

            $producer->attributeValues()->sync($this->attributeIdsFromLabels($attributes, $item['attributes']));
            $created[$item['name']] = $producer;
        }

        return $created;
    }

    private function seedWines(array $attributes, array $producers): void
    {
        $items = [
            ['producer' => 'Phelan Farm', 'name' => 'Brij Oso Rouge', 'slug' => 'brij-oso-rouge', 'image' => 'vinos/phelan oso brij rouge.webp', 'description' => 'Mouvedre - Grenache de viñedos costeros. Fresco, expresivo, con fruta oscura y tensión salina.', 'attributes' => ['Tipo' => 'Blend', 'Año' => '2023', 'Formato' => 'Botella']],
            ['producer' => 'Phelan Farm', 'name' => 'Brij Chardonnay', 'slug' => 'brij-chardonnay', 'image' => 'vinos/phelan brij chardonnay.jpg', 'description' => 'Chardonnay fresca y mineral, marcada por la influencia del Pacífico. Salinidad, energía y un final largo.', 'attributes' => ['Tipo' => 'Chardonnay', 'Año' => '2023', 'Formato' => 'Botella']],
            ['producer' => 'Phelan Farm', 'name' => 'Phelan Farm Pinot Noir', 'slug' => 'phelan-farm-pinot-noir', 'image' => 'vinos/Phelan-Farm-Pinot-Noir.png', 'description' => 'Pinot Noir de clima frío californiano. Equilibrio, claridad y una expresión precisa del lugar.', 'attributes' => ['Tipo' => 'Pinot Noir', 'Año' => '2024', 'Formato' => 'Botella']],
            ['producer' => 'Phelan Farm', 'name' => 'Phelan Farm Chardonnay', 'slug' => 'phelan-farm-chardonnay', 'image' => 'vinos/Phelan Farm Chardonnay.webp', 'description' => 'Chardonnay con tensión y claridad. Vinificación precisa que prioriza la energía por sobre la potencia.', 'attributes' => ['Tipo' => 'Chardonnay', 'Año' => '2023', 'Formato' => 'Botella']],
            ['producer' => 'Chiara Condello', 'name' => 'Predappio', 'slug' => 'predappio-2022', 'image' => 'vinos/Chiara-condello-Predappio.png', 'description' => 'Etiqueta de Chiara Condello presentada en la selección del sitio.', 'attributes' => ['Año' => '2022', 'Formato' => 'Botella']],
            ['producer' => 'Chiara Condello', 'name' => 'Le Lucciole', 'slug' => 'le-lucciole-2021', 'image' => 'vinos/Chiara-Condello-Le-Lucciole.png', 'description' => 'Etiqueta destacada en la selección de Chiara Condello.', 'attributes' => ['Año' => '2021', 'Formato' => 'Botella']],
            ['producer' => 'Chiara Condello', 'name' => 'Lo Starlisco', 'slug' => 'lo-starlisco-2020', 'image' => 'vinos/Chiara-Condello-Lo-Starlisco.png', 'description' => 'Etiqueta incluida en la selección de la bodega.', 'attributes' => ['Año' => '2020', 'Formato' => 'Botella']],
            ['producer' => 'Chiara Condello', 'name' => 'Predappio Magnum', 'slug' => 'predappio-2022-magnum', 'image' => 'vinos/Chiara-condello-Predappio.png', 'description' => 'Versión magnum de Predappio.', 'attributes' => ['Año' => '2022', 'Formato' => 'Magnum']],
            ['producer' => 'Chiara Condello', 'name' => 'Le Lucciole Magnum', 'slug' => 'le-lucciole-2021-magnum', 'image' => 'vinos/Chiara-Condello-Le-Lucciole.png', 'description' => 'Versión magnum de Le Lucciole.', 'attributes' => ['Año' => '2021', 'Formato' => 'Magnum']],
            ['producer' => 'Nicolas Joly', 'name' => 'Coulée de la Serrant', 'slug' => 'coulee-de-la-serrant', 'image' => 'vinos/joly coulee de la serrant.webp', 'description' => 'Botella presente en la selección principal del sitio.', 'attributes' => ['Tipo' => 'Chenin Blanc', 'Formato' => 'Botella']],
            ['producer' => 'Nicolas Joly', 'name' => 'Les Vieux Clos', 'slug' => 'les-vieux-clos', 'image' => 'vinos/joly les vieux clos.webp', 'description' => 'Botella presente en la selección principal del sitio.', 'attributes' => ['Tipo' => 'Chenin Blanc', 'Formato' => 'Botella']],
            ['producer' => 'Domaine aux Moines', 'name' => 'Domaine aux Moines', 'slug' => 'domaine-aux-moines', 'image' => 'vinos/domaine aux moines.webp', 'description' => 'Etiqueta destacada del productor en el home.', 'attributes' => ['Tipo' => 'Chenin Blanc', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Filzen Chardonnay', 'slug' => 'filzen-chardonnay', 'image' => 'vinos/wasenhaus filzen chardonnay.png', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Chardonnay', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Grand Ordinaire', 'slug' => 'grand-ordinaire', 'image' => 'vinos/Wasenhaus-Grand-Ordinaire.webp', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Spätburgunder', 'slug' => 'spatburgunder', 'image' => 'vinos/wasenhaus spatburgunder.webp', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Spätburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Hohlen Chardonnay', 'slug' => 'hohlen-chardonnay', 'image' => 'vinos/wasenhaus hohlen chardonnay.webp', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Chardonnay', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Möhlin Spätburgunder', 'slug' => 'mohlin-spatburgunder', 'image' => 'vinos/wasenhaus mohlin spatburgunder.webp', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Spätburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Weissburgunder', 'slug' => 'weissburgunder', 'image' => 'vinos/wasenhaus weissburgunder.png', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Weissburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Kalk Spätburgunder', 'slug' => 'kalk-spatburgunder', 'image' => 'vinos/wasenhaus kalk spatburgunder.webp', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Spätburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Bellen Spätburgunder', 'slug' => 'bellen-spatburgunder', 'image' => 'vinos/wasenhaus-bellen-spatburgunder.png', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Spätburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Möhlin Weissburgunder', 'slug' => 'mohlin-weissburgunder', 'image' => 'vinos/wasenhaus mohlin weissburgunder.png', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Weissburgunder', 'Formato' => 'Botella']],
            ['producer' => 'Wasenhaus', 'name' => 'Hohlen Spätburgunder', 'slug' => 'hohlen-spatburgunder', 'image' => 'vinos/wasenhaus-hohlen-spatburgunder.png', 'description' => 'Etiqueta listada en el home de Pepichis.', 'attributes' => ['Tipo' => 'Spätburgunder', 'Formato' => 'Botella']],
        ];

        foreach ($items as $item) {
            $sortOrder = Wine::where('producer_id', $producers[$item['producer']]->id)->count() + 1;

            $wine = Wine::create([
                'producer_id' => $producers[$item['producer']]->id,
                'name' => $item['name'],
                'slug' => $item['slug'],
                'sort_order' => $sortOrder,
                'image_path' => $this->importImage($item['image'], 'wines'),
                'short_description' => $item['description'],
                'long_description' => $item['description'],
            ]);

            $wine->attributeValues()->sync($this->attributeIdsFromLabels($attributes, $item['attributes']));
        }
    }

    private function attributeIdsFromLabels(array $attributes, array $pairs): array
    {
        $ids = [];

        foreach ($pairs as $attributeName => $value) {
            $attribute = $attributes[$attributeName] ?? null;

            if (! $attribute) {
                continue;
            }

            $attributeValue = $attribute->values->firstWhere('value', $value);

            if ($attributeValue) {
                $ids[] = $attributeValue->id;
            }
        }

        return $ids;
    }

    private function importImage(string $relativePath, string $folder): ?string
    {
        $sourcePath = base_path('..' . DIRECTORY_SEPARATOR . $relativePath);

        if (! File::exists($sourcePath)) {
            return null;
        }

        $targetDirectory = public_path('uploads/seed/' . $folder);
        File::ensureDirectoryExists($targetDirectory);

        $extension = pathinfo($relativePath, PATHINFO_EXTENSION);
        $baseName = Str::slug(pathinfo($relativePath, PATHINFO_FILENAME));
        $fileName = $baseName . ($extension ? '.' . $extension : '');
        $destination = $targetDirectory . DIRECTORY_SEPARATOR . $fileName;

        File::copy($sourcePath, $destination);

        return 'uploads/seed/' . $folder . '/' . $fileName;
    }
}
