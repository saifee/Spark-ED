<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBehaviourPointLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_behaviour_point_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('skill_id')->unsigned()->nullable();
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('points')->nullable();
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
        Schema::table('student_behaviour_point_logs', function(Blueprint $table)
        {
            $table->dropForeign('student_behaviour_point_logs_skill_id_foreign');
            $table->dropForeign('student_behaviour_point_logs_employee_id_foreign');
        });

        Schema::dropIfExists('student_behaviour_point_logs');
    }
}
