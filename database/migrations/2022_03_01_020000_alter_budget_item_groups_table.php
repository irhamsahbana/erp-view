<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBudgetItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_item_groups', function (Blueprint $table) {
            $table->index('report_category_id');
            $table->foreign('report_category_id')
            ->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_item_groups', function (Blueprint $table) {
            $table->dropForeign(['report_category_id']);
            $table->dropIndex(['report_category_id']);
        });
    }
}
