<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosDamageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('productos_damage', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('producto_id');
        //     $table->string('descripcion')->nullable();
        //     $table->enum('tipo_damage', ['fabrica', 'paciente']);
        //     $table->unsignedInteger('user_id')->nullable();
        //     $table->timestamps();

        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->foreign('producto_id')->references('id')->on('productos');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_damage');
    }
}
