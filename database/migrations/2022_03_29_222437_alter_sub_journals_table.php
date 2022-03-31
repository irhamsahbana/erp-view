<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_journals', function (Blueprint $table) {
            $table->index('budget_item_group_id');
            $table->foreign('budget_item_group_id')
            ->references('id')->on('budget_item_groups');

            $table->index('journal_id');
            $table->foreign('journal_id')
            ->references('id')->on('journals');

            $table->index('budget_item_id');
            $table->foreign('budget_item_id')
            ->references('id')->on('budget_items');

            $table->index('sub_budget_item_id');
            $table->foreign('sub_budget_item_id')
            ->references('id')->on('sub_budget_items');

            $table->index('project_id');
            $table->foreign('project_id')
            ->references('id')->on('projects');

            $table->index('normal_balance_id');
            $table->foreign('normal_balance_id')
            ->references('id')->on('rit_balances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['budget_item_group_id']);
            $table->dropIndex(['budget_item_group_id']);

            $table->dropForeign(['journal_id']);
            $table->dropIndex(['journal_id']);

            $table->dropForeign(['budget_item_id']);
            $table->dropIndex(['budget_item_id']);
            
            $table->dropForeign(['sub_budget_item_id']);
            $table->dropIndex(['sub_budget_item_id']);

            $table->dropForeign(['project_id']);
            $table->dropIndex(['project_id']);

            $table->dropForeign(['normal_balance_id']);
            $table->dropIndex(['normal_balance_id']);
        });
    }
}
