<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBehaviourPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_behaviour_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_record_id')->unsigned()->nullable();
            $table->foreign('student_record_id')->references('id')->on('student_records')->onDelete('cascade');
            $table->integer('total')->default(0);
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
        Schema::table('student_behaviour_points', function(Blueprint $table)
        {
            $table->dropForeign('student_behaviour_points_student_record_id_foreign');
        });

        Schema::dropIfExists('student_behaviour_points');
    }
}
