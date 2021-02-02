<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPreciosHistorialDeCambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_cambios_venta', function (Blueprint $table) {
            //
            $table->decimal('precioOri')->nullable();
            $table->decimal('precioNew')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_cambios_venta', function (Blueprint $table) {
            //
            $table->dropColumn('precioOri');
             $table->dropColumn('precioNew');
        });
    }
}
