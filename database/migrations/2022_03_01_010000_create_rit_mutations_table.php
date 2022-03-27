<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRitMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rit_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('material_mutation_id');
            $table->unsignedTinyInteger('transaction_type')->comment('1 = penambahan, 2 = pengurangan');
            $table->float('amount', 15, 2)->comment('biaya');
            $table->string('notes')->nullable();
            $table->boolean('is_open')->default(false);
            $table->date('created')->nullable();
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
        Schema::dropIfExists('rit_mutations');
    }
}
