<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->bigInteger('academic_session_id')->unsigned()->nullable();
            $table->foreign('academic_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->bigInteger('batch_id')->unsigned()->nullable();
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('positive')->default(1);
            $table->integer('points')->default(1);
            $table->bigInteger('skill_icon_id')->unsigned()->nullable();
            $table->foreign('skill_icon_id')->references('id')->on('skill_icons')->onDelete('set null');
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
        Schema::table('skills', function(Blueprint $table)
        {
            $table->dropForeign('skills_academic_session_id_foreign');
            $table->dropForeign('skills_batch_id_foreign');
            $table->dropForeign('skills_skill_icon_id_foreign');
        });

        Schema::dropIfExists('skills');
    }
}
