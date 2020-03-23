<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsoCfdiToDatosFiscalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_fiscales', function (Blueprint $table) {
            $table->string('uso_cfdi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_fiscales', function (Blueprint $table) {
            $table->dropColumn('uso_cfdi');
        });
    }
}
