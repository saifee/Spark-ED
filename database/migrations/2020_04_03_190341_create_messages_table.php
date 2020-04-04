<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->bigInteger('student_parent_id')->unsigned()->nullable();
            $table->foreign('student_parent_id')->references('id')->on('student_parents')->onDelete('cascade');
            $table->bigInteger('student_record_id')->unsigned()->nullable();
            $table->foreign('student_record_id')->references('id')->on('students')->onDelete('cascade');
            $table->text('content')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->softDeletes();
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
        Schema::table('messages', function(Blueprint $table)
        {
            $table->dropForeign('messages_employee_id_foreign');
            $table->dropForeign('messages_student_parent_id_foreign');
            $table->dropForeign('messages_student_record_id_foreign');
        });

        Schema::dropIfExists('messages');
    }
}
