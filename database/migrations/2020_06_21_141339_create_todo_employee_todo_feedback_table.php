<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoEmployeeTodoFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_employee_todo_feedback', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('todo_employee_id')->unsigned()->nullable();
            $table->foreign('todo_employee_id')->references('id')->on('todo_employees')->onDelete('cascade');
            $table->bigInteger('todo_feedback_id')->unsigned()->nullable();
            $table->foreign('todo_feedback_id')->references('id')->on('todo_feedback')->onDelete('cascade');
            $table->text('comments')->nullable();
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
        Schema::table('todo_employee_todo_feedback', function(Blueprint $table)
        {
            $table->dropForeign('todo_employee_todo_feedback_user_id_foreign');
            $table->dropForeign('todo_employee_todo_feedback_todo_employee_id_foreign');
            $table->dropForeign('todo_employee_todo_feedback_todo_feedback_id_foreign');
        });

        Schema::dropIfExists('todo_employee_todo_feedback');
    }
}
