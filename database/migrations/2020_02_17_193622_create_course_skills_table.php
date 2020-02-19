<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->bigInteger('academic_session_id')->unsigned()->nullable();
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->bigInteger('course_id')->unsigned()->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->bigInteger('batch_id')->unsigned()->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('positive')->default(1);
            $table->integer('points')->default(1);
            $table->bigInteger('course_skill_icon_id')->unsigned()->nullable();
            $table->foreign('course_skill_icon_id')->references('id')->on('course_skill_icons')->onDelete('set null');
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
        Schema::table('course_skills', function(Blueprint $table)
        {
            $table->dropForeign('course_skills_academic_session_id_foreign');
            $table->dropForeign('course_skills_course_id_foreign');
            $table->dropForeign('course_skills_batch_id_foreign');
            $table->dropForeign('course_skills_course_skill_icon_id_foreign');
        });

        Schema::dropIfExists('course_skills');
    }
}
