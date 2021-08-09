<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsCrmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crms', function (Blueprint $table) {
            //
             $table->integer('contesto_id')->nullable();
              $table->string('comentarios_uso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crms', function (Blueprint $table) {
            //
             $table->dropColumn('contesto_id');
              $table->dropColumn('comentarios_uso');
        });
    }
}
