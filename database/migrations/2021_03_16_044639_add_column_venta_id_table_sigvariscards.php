<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVentaIdTableSigvariscards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sigvariscards', function (Blueprint $table) {
            //
            $table->integer('venta_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sigvariscards', function (Blueprint $table) {
            //
             $table->dropColumn('venta_id');
        });
    }
}
