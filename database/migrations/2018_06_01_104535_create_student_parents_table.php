<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_parents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('father_name')->nullable();
            $table->date('father_date_of_birth')->nullable();
            $table->string('father_qualification')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_annual_income')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_contact_number_1')->nullable();
            $table->string('father_contact_number_2')->nullable();
            $table->string('father_photo')->nullable();
            $table->string('father_unique_identification_number',20)->nullable();
            $table->string('mother_name')->nullable();
            $table->date('mother_date_of_birth')->nullable();
            $table->string('mother_qualification')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_annual_income')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('mother_contact_number_1')->nullable();
            $table->string('mother_contact_number_2')->nullable();
            $table->string('mother_photo')->nullable();
            $table->string('mother_unique_identification_number',20)->nullable();
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
        Schema::table('student_parents', function(Blueprint $table)
        {
            $table->dropForeign('student_parents_user_id_foreign');
        });

        Schema::dropIfExists('student_parents');
    }
}
