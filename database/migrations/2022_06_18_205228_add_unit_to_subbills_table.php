<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitToSubbillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subbills', function (Blueprint $table) {
            $table->string("unit");
            $table->integer("unit_price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subbills', function (Blueprint $table) {
            $table->dropColomn("unit");
            $table->dropColomn("unit_price");
        });
    }
}
