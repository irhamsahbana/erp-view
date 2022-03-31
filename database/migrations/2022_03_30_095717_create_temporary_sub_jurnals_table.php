<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporarySubJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_sub_jurnals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_process')->nullable(); //
            $table->string('status_data')->nullable(); //
            $table->foreignId('budget_item_group_id');
            $table->foreignId('journal_id');
            $table->foreignId('budget_item_id');
            $table->foreignId('sub_budget_item_id');
            $table->foreignId('project_id')->nullable();
            $table->foreignId('normal_balance_id');
            $table->foreignId('user_id');
            $table->float('amount');
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
        Schema::dropIfExists('temporary_sub_jurnals');
    }
}
