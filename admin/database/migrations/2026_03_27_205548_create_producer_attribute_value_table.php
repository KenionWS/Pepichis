<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producer_attribute_value', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['producer_id', 'attribute_value_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('producer_attribute_value');
    }
};
