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
            $table->bigInteger('sender_id')->unsigned()->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('receiver_id')->unsigned()->nullable();
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign('messages_sender_id_foreign');
            $table->dropForeign('messages_receiver_id_foreign');
            $table->dropForeign('messages_student_record_id_foreign');
        });

        Schema::dropIfExists('messages');
    }
}
