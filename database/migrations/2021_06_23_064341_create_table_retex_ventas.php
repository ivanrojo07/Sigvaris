<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRetexVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retex_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('venta_id');
            $table->string('SKU');
            $table->decimal('total_a_pagar');
            $table->string('folio');
            $table->date('fecha_fin');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_retex_ventas');
    }
}
