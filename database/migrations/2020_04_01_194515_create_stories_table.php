<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->bigInteger('academic_session_id')->unsigned()->nullable();
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->bigInteger('batch_id')->unsigned()->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('type')->nullable(); // text, photo, file
            $table->text('content')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedInteger('likes_count')->nullable()->default(0);
            $table->unsignedInteger('comments_count')->nullable()->default(0);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('stories', function(Blueprint $table)
        {
            $table->dropForeign('stories_academic_session_id_foreign');
            $table->dropForeign('stories_batch_id_foreign');
            $table->dropForeign('stories_user_id_foreign');
        });

        Schema::dropIfExists('stories');
    }
}
