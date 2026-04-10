<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('slug');
        });

        $producerIds = DB::table('wines')
            ->select('producer_id')
            ->distinct()
            ->pluck('producer_id');

        foreach ($producerIds as $producerId) {
            $wines = DB::table('wines')
                ->where('producer_id', $producerId)
                ->orderBy('id')
                ->get(['id']);

            foreach ($wines as $index => $wine) {
                DB::table('wines')
                    ->where('id', $wine->id)
                    ->update(['sort_order' => $index + 1]);
            }
        }
    }

    public function down()
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
