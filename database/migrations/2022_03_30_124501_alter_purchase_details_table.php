<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->index('purchase_id');
            $table->foreign('purchase_id')
            ->references('id')->on('purchases');

            $table->index('branch_id');
            $table->foreign('branch_id')
            ->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
            $table->dropIndex(['purchase_id']);

            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
        });
    }
}
