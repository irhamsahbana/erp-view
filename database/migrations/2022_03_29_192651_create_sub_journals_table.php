<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_item_group_id');
            $table->unsignedBigInteger('journal_id');
            $table->unsignedBigInteger('budget_item_id');
            $table->unsignedBigInteger('sub_budget_item_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('normal_balance_id');
            $table->unsignedBigInteger('user_id');
            $table->float('amount', 15, 2);
            $table->longText('notes')->nullable();
            $table->boolean('is_open')->default(false);
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
        Schema::dropIfExists('sub_journals');
    }
}
