<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('producers', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('slug');
        });

        $producers = DB::table('producers')->orderBy('id')->get(['id']);

        foreach ($producers as $index => $producer) {
            DB::table('producers')
                ->where('id', $producer->id)
                ->update(['sort_order' => $index + 1]);
        }
    }

    public function down()
    {
        Schema::table('producers', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
