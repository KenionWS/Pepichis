<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('item_type', 50);
            $table->string('item_value');
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('menu_items')->insert([
            [
                'label' => 'Nosotros',
                'item_type' => MenuItem::TYPE_HOME_SECTION,
                'item_value' => 'nosotros',
                'open_in_new_tab' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Seleccion',
                'item_type' => MenuItem::TYPE_HOME_SECTION,
                'item_value' => 'seleccion',
                'open_in_new_tab' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Productores',
                'item_type' => MenuItem::TYPE_HOME_SECTION,
                'item_value' => 'productores',
                'open_in_new_tab' => false,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Notas',
                'item_type' => MenuItem::TYPE_ROUTE,
                'item_value' => 'front.notes.index',
                'open_in_new_tab' => false,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Contacto',
                'item_type' => MenuItem::TYPE_HOME_SECTION,
                'item_value' => 'contacto',
                'open_in_new_tab' => false,
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
