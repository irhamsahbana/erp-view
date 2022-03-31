<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->index('branch_id');
            $table->foreign('branch_id')
            ->references('id')->on('branches');

            $table->index('vendor_id');
            $table->foreign('vendor_id')
            ->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);

            $table->dropForeign(['vendor_id']);
            $table->dropIndex(['vendor_id']);
        });
    }
}
