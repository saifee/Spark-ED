<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->string('name')->nullable();
            $table->boolean('positive')->default(1)->nullable();
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
        Schema::table('employee_skills', function(Blueprint $table)
        {
            $table->dropForeign('employee_skills_skill_icon_id_foreign');
        });

        Schema::dropIfExists('employee_skills');
    }
}
