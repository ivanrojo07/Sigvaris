<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInTableVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            //
            $table->decimal('num_transferencia')->nullable();
            $table->string('folio_transferencia')->nullable();
            $table->decimal('num_deposito')->nullable();
            $table->string('folio_deposito')->nullable();
                  
                   
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
            //
             $table->dropColumn('num_transferencia');
              $table->dropColumn('folio_transferencia');
               $table->dropColumn('num_déposito');
              $table->dropColumn('folio_deposito');
        });
    }
}
