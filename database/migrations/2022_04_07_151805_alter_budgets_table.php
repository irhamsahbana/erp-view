<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->index('branch_id');
            $table->foreign('branch_id')
            ->references('id')->on('branches');

            $table->index('project_id');
            $table->foreign('project_id')
            ->references('id')->on('projects');

            $table->index('budget_item_group_id');
            $table->foreign('budget_item_group_id')
            ->references('id')->on('budget_item_groups');

            $table->index('budget_item_id');
            $table->foreign('budget_item_id')
            ->references('id')->on('budget_items');

            $table->index('sub_budget_item_id');
            $table->foreign('sub_budget_item_id')
            ->references('id')->on('sub_budget_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);

            $table->dropForeign(['project_id']);
            $table->dropIndex(['project_id']);

            $table->dropForeign(['budget_item_group_id']);
            $table->dropIndex(['budget_item_group_id']);

            $table->dropForeign(['budget_item_id']);
            $table->dropIndex(['budget_item_id']);

            $table->dropForeign(['sub_budget_item_id']);
            $table->dropIndex(['sub_budget_item_id']);
        });
    }
}
