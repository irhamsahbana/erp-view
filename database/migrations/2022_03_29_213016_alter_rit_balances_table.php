<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRitBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rit_balances', function (Blueprint $table) {
            $table->index('branch_id');
            $table->foreign('branch_id')
            ->references('id')->on('branches');

            $table->index('project_id');
            $table->foreign('project_id')
            ->references('id')->on('projects');

            $table->index('driver_id');
            $table->foreign('driver_id')
            ->references('id')->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rit_balances', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);

            $table->dropForeign(['project_id']);
            $table->dropIndex(['project_id']);

            $table->dropForeign(['material_mutation_id']);
            $table->dropIndex(['material_mutation_id']);

            $table->dropForeign(['driver_id']);
            $table->dropIndex(['driver_id']);
        });
    }
}
