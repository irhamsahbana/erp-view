<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function(Blueprint $table) {
            // $table->index('receivable_vendor_id');
            $table->foreign('bill_vendor_id')
            ->references('id')->on('bill_vendors');

            $table->foreign('branch_id')
            ->references('id')->on('branches');
        });
        Schema::table('bill_balances', function(Blueprint $table) {
            // $table->index('receivable_vendor_id');
            $table->foreign('bill_vendor_id')
            ->references('id')->on('bill_vendors');

            $table->foreign('branch_id')
            ->references('id')->on('branches');
        });
        Schema::table('subbills', function(Blueprint $table) {
            // $table->index('receivable_vendor_id');
            $table->foreign('bill_vendor_id')
            ->references('id')->on('bill_vendors');
            $table->foreign('bill_id')
            ->references('id')->on('bills');
            $table->foreign('bill_item_id')
            ->references('id')->on('bill_items');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function(Blueprint $table) {

            $table->dropForeign(['bill_vendor_id']);

            $table->dropForeign(['branch_id']);

        });
        Schema::table('bill_balances', function(Blueprint $table) {

            $table->dropForeign(['bill_vendor_id']);

            $table->dropForeign(['branch_id']);

        });
        Schema::table('subbills', function(Blueprint $table) {

            $table->dropForeign(['bill_vendor_id']);

            $table->dropForeign(['bill_id']);

            $table->dropForeign(['bill_item_id']);

        });
    }
}
