<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsInTableSigpesosventa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sigpesosventa', function (Blueprint $table) {
            //
            $table->integer('paciente_id')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('usado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sigpesosventa', function (Blueprint $table) {
            //
            $table->dropColumn('paciente_id');
              $table->dropColumn('tipo');
                $table->dropColumn('usado');
        });
    }
}
