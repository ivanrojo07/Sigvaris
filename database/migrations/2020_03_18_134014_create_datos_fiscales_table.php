<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosFiscalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_fiscales', function (Blueprint $table) {
            // $table->increments('id');
            // $table->unsignedInteger('paciente_id');
            // $table->string('tipo_persona')->nullable();
            // $table->string('nombre_o_razon_social')->nullable();
            // $table->string('regimen_fiscal')->nullable();
            // $table->string('homoclave')->nullable();
            // $table->string('correo')->nullable();
            // $table->string('rfc')->nullable();
            // $table->string('calle')->nullable();
            // $table->integer('num_ext')->nullable();
            // $table->integer('num_int')->nullable();
            // $table->string('colonia')->nullable();
            // $table->string('ciudad')->nullable();
            // $table->string('alcaldia_o_municipio')->nullable();
            // $table->string('estado')->nullable();
            // $table->string('codigo_postal')->nullable();
            // $table->timestamps();

            // $table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_fiscales');
    }
}
