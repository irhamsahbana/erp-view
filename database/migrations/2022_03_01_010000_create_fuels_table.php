<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuels', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->float('amount', 15, 2);
            $table->string('notes')->nullable();
            $table->boolean('is_open')->default(false);
            $table->date('created');
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
        Schema::dropIfExists('fuels');
    }
}
