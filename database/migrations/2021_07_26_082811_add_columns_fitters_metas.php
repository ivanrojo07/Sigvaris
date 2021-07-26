<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsFittersMetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fitter_metas', function (Blueprint $table) {
            //
            $table->integer('ventas_obsoletos')->nullable();
            $table->integer('calcetin')->nullable();
            $table->integer('leggings')->nullable();
            $table->integer('muslo')->nullable();
            $table->integer('media')->nullable();
            $table->integer('panti')->nullable();
            $table->integer('tobimedias')->nullable();
            $table->integer('pz_mayor')->nullable();
            $table->integer('pz_menor')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fitter_metas', function (Blueprint $table) {
            //
            $table->dropColumn('ventas_obsoletos');
            $table->dropColumn('calcetin');
            $table->dropColumn('leggings');
            $table->dropColumn('muslo');
            $table->dropColumn('media');
            $table->dropColumn('panti');
            $table->dropColumn('tobimedias');
            $table->dropColumn('pz_mayor');
            $table->dropColumn('pz_menor');
        });
    }
}
