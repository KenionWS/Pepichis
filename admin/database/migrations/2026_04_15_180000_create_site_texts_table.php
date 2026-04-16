<?php

use App\Models\SiteText;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_texts', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->text('body');
            $table->timestamps();
        });

        DB::table('site_texts')->insert(array_merge(
            SiteText::defaultAbout(),
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('site_texts');
    }
};
