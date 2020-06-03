<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialSurtidoTable extends Migration
{
     
    public function up()
    {
        Schema::create('historial_surtido', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('producto_id')->unsigned();
            //$table->foreign('producto_id')->references('id')->on('productos');
            $table->integer('numero')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historial_surtido');
    }
}
