<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubBudgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_budget_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_category_id');
            $table->unsignedBigInteger('budget_item_group_id');
            $table->unsignedBigInteger('budget_item_id');
            $table->unsignedBigInteger('normal_balance_id');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_budget_items');
    }
}
