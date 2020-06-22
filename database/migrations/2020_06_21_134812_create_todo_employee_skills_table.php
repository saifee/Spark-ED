<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoEmployeeSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_employee_skills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('todo_id')->unsigned()->nullable();
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->bigInteger('employee_skill_id')->unsigned()->nullable();
            $table->foreign('employee_skill_id')->references('id')->on('employee_skills')->onDelete('cascade');
            $table->unsignedInteger('percentage')->nullable()->default(0);
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
        Schema::table('todo_employee_skills', function(Blueprint $table)
        {
            $table->dropForeign('todo_employee_skills_todo_id_foreign');
            $table->dropForeign('todo_employee_skills_employee_skill_id_foreign');
        });

        Schema::dropIfExists('todo_employee_skills');
    }
}
