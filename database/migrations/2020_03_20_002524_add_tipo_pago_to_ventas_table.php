<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoPagoToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            // $table->integer('PagoTarjeta')->unsigned()->nullable();
            // $table->integer('PagoEfectivo')->unsigned()->nullable();
            // $table->integer('mesesPago')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            // $table->dropColumn('pagoTarjeta');
            // $table->dropColumn('pagoEfectivo');
            // $table->dropColumn('mesesPago');
        });
    }
}
