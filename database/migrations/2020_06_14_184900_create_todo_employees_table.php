<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('todo_id')->unsigned()->nullable();
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->bigInteger('assigned_by')->unsigned()->nullable();
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(0);
            $table->date('date')->nullable();
            $table->dateTime('completed_at')->nullable();
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
        Schema::table('todo_employees', function(Blueprint $table)
        {
            $table->dropForeign('todo_employees_assigned_by_foreign');
            $table->dropForeign('todo_employees_user_id_foreign');
            $table->dropForeign('todo_employees_todo_id_foreign');
        });

        Schema::dropIfExists('todo_employees');
    }
}
