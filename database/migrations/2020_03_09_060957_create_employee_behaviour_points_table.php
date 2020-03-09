<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeBehaviourPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_behaviour_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_term_id')->unsigned()->nullable();
            $table->foreign('employee_term_id')->references('id')->on('employee_terms')->onDelete('cascade');
            $table->integer('total')->default(0);
            $table->text('options')->nullable();
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
        Schema::table('employee_behaviour_points', function(Blueprint $table)
        {
            $table->dropForeign('employee_behaviour_points_employee_term_id_foreign');
        });

        Schema::dropIfExists('employee_behaviour_points');
    }
}
