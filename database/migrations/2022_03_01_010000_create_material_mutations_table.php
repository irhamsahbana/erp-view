<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_mutations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedTinyInteger('type')->comment('1 = Masuk, 2 = Keluar');
            $table->float('material_price', 15, 2)->nullable();
            $table->float('volume', 15, 2);
            $table->float('cost', 15, 2)->nullable();
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
        Schema::dropIfExists('material_mutations');
    }
}
