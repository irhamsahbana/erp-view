<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedTinyInteger('type')->comment('1 = Hutang, 2 = Piutang');
            $table->unsignedTinyInteger('transaction_type')->comment('1 = Penambahan, 2 = Pengurangan');
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
        Schema::dropIfExists('debt_mutations');
    }
}
