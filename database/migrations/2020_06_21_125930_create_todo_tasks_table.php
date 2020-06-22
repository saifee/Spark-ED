<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('todo_id')->unsigned()->nullable();
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->text('description')->nullable();
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
        Schema::table('todo_tasks', function(Blueprint $table)
        {
            $table->dropForeign('todo_tasks_todo_id_foreign');
        });

        Schema::dropIfExists('todo_tasks');
    }
}
