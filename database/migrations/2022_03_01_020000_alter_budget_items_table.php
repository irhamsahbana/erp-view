<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBudgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->index('report_category_id');
            $table->foreign('report_category_id')
            ->references('id')->on('categories');

            $table->index('budget_item_group_id');
            $table->foreign('budget_item_group_id')
            ->references('id')->on('budget_item_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_items', function (Blueprint $table) {
            $table->dropForeign(['report_category_id']);
            $table->dropIndex(['report_category_id']);

            $table->dropForeign(['budget_item_group_id']);
            $table->dropIndex(['budget_item_group_id']);
        });
    }
}
