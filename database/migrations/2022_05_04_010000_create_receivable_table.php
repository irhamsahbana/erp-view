<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivable', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('vendor_id');
            $table->float('amount', 15,2);
            $table->date('send_date');
            $table->date('pay_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->text('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivable');
    }
}
