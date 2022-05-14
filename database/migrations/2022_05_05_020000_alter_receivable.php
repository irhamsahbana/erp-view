<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReceivable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('receivables', function(Blueprint $table) {
            // $table->index('receivable_vendor_id');
            $table->foreign('receivable_vendor_id')
            ->references('id')->on('receivable_vendor');

            // $table->index('branch_id');
            $table->foreign('branch_id')
            ->references('id')->on('branches');

            // $table->index('project_id');
            $table->foreign('project_id')
            ->references('id')->on('projects');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::table('receivables', function(Blueprint $table) {
            $table->dropForeign(['receivable_vendor_id']);
            // $table->dropIndex(['receivable_vendor_id']);

            $table->dropForeign(['branch_id']);
            // $table->dropIndex(['branch_id']);

            $table->dropForeign(['project_id']);
            // $table->dropIndex(['project_id']);
        });

        // Schema::table('receivable_balances', function(Blueprint $table) {
        //     $table->dropForeign(['receivable_vendor_id']);
        //     $table->dropIndex(['receivable_vendor_id']);

        //     $table->dropForeign(['branch_id']);
        //     $table->dropIndex(['branch_id']);

        //     $table->dropForeign(['project_id']);
        //     $table->dropIndex(['project_id']);
        // });

        // Schema::table('receivable_vendor', function(Blueprint $table) {

        //     $table->dropForeign(['branch_id']);
        //     $table->dropIndex(['branch_id']);

        // });
    }
}
