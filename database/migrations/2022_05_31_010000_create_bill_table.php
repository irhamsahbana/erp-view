<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('bill_vendor_id');
            $table->date('due_date');
            $table->date('recive_date');
            $table->date('pay_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->float('amount', 15,2);
            $table->string('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
