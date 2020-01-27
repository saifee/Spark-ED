<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRelationsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_parents', function (Blueprint $table) {
            $table->string('first_guardian_relation')->default('father')->after('father_name');
            $table->string('second_guardian_relation')->default('mother')->after('mother_name');
            $table->string('third_guardian_name')->nullable()->after('second_guardian_relation');
            $table->string('third_guardian_relation')->nullable()->after('third_guardian_name');
            $table->renameColumn('father_name', 'first_guardian_name');
            $table->renameColumn('father_date_of_birth', 'first_guardian_date_of_birth');
            $table->renameColumn('father_qualification', 'first_guardian_qualification');
            $table->renameColumn('father_occupation', 'first_guardian_occupation');
            $table->renameColumn('father_annual_income', 'first_guardian_annual_income');
            $table->renameColumn('father_email', 'first_guardian_email');
            $table->renameColumn('father_contact_number_1', 'first_guardian_contact_number_1');
            $table->renameColumn('father_contact_number_2', 'first_guardian_contact_number_2');
            $table->renameColumn('father_photo', 'first_guardian_photo');
            $table->renameColumn('father_unique_identification_number', 'first_guardian_unique_identification_number');
            $table->renameColumn('mother_name', 'second_guardian_name');
            $table->renameColumn('mother_date_of_birth', 'second_guardian_date_of_birth');
            $table->renameColumn('mother_qualification', 'second_guardian_qualification');
            $table->renameColumn('mother_occupation', 'second_guardian_occupation');
            $table->renameColumn('mother_annual_income', 'second_guardian_annual_income');
            $table->renameColumn('mother_email', 'second_guardian_email');
            $table->renameColumn('mother_contact_number_1', 'second_guardian_contact_number_1');
            $table->renameColumn('mother_contact_number_2', 'second_guardian_contact_number_2');
            $table->renameColumn('mother_photo', 'second_guardian_photo');
            $table->renameColumn('mother_unique_identification_number', 'second_guardian_unique_identification_number');
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('first_guardian_relation')->default('father')->after('father_name');
            $table->string('second_guardian_relation')->default('mother')->after('mother_name');
            $table->string('third_guardian_relation')->nullable()->after('second_guardian_relation');
            $table->renameColumn('father_name', 'first_guardian_name');
            $table->renameColumn('mother_name', 'second_guardian_name');
            $table->renameColumn('guardian_name', 'third_guardian_name');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('spouse_name')->nullable()->after('mother_name');
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
            $table->renameColumn('first_guardian_name', 'father_name');
            $table->dropColumn('first_guardian_relation');
            $table->renameColumn('second_guardian_name', 'mother_name');
            $table->dropColumn('second_guardian_relation');
            $table->dropColumn('third_guardian_name');
            $table->dropColumn('third_guardian_relation');
            $table->renameColumn('first_guardian_date_of_birth', 'father_date_of_birth');
            $table->renameColumn('first_guardian_qualification', 'father_qualification');
            $table->renameColumn('first_guardian_occupation', 'father_occupation');
            $table->renameColumn('first_guardian_annual_income', 'father_annual_income');
            $table->renameColumn('first_guardian_email', 'father_email');
            $table->renameColumn('first_guardian_contact_number_1', 'father_contact_number_1');
            $table->renameColumn('first_guardian_contact_number_2', 'father_contact_number_2');
            $table->renameColumn('first_guardian_photo', 'father_photo');
            $table->renameColumn('first_guardian_unique_identification_number', 'father_unique_identification_number');
            $table->renameColumn('second_guardian_date_of_birth', 'mother_date_of_birth');
            $table->renameColumn('second_guardian_qualification', 'mother_qualification');
            $table->renameColumn('second_guardian_occupation', 'mother_occupation');
            $table->renameColumn('second_guardian_annual_income', 'mother_annual_income');
            $table->renameColumn('second_guardian_email', 'mother_email');
            $table->renameColumn('second_guardian_contact_number_1', 'mother_contact_number_1');
            $table->renameColumn('second_guardian_contact_number_2', 'mother_contact_number_2');
            $table->renameColumn('second_guardian_photo', 'mother_photo');
            $table->renameColumn('second_guardian_unique_identification_number', 'mother_unique_identification_number');
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->renameColumn('first_guardian_name', 'father_name');
            $table->dropColumn('first_guardian_relation');
            $table->renameColumn('second_guardian_name', 'mother_name');
            $table->dropColumn('second_guardian_relation');
            $table->renameColumn('third_guardian_name', 'guardian_name');
            $table->dropColumn('third_guardian_relation');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('spouse_name');
        });
    }
}
