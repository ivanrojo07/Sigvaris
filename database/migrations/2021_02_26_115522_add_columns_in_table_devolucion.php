<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInTableDevolucion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devoluciones', function (Blueprint $table) {
            //
             $table->decimal('sigpesos_d')->nullable();
             $table->decimal('saldo_d')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devoluciones', function (Blueprint $table) {
            //
            $table->dropColumn('sigpesos_d');
              $table->dropColumn('saldo_d');
        });
    }
}
