<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->boolean('show_on_home')->default(false)->after('sort_order');
        });

        DB::table('wines')->update([
            'show_on_home' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->dropColumn('show_on_home');
        });
    }
};
