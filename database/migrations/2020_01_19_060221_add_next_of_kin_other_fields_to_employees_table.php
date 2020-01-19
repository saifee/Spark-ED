<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextOfKinOtherFieldsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('next_of_kin_relation')->nullable();
            $table->string('next_of_kin_id')->nullable();
            $table->string('next_of_kin_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'next_of_kin_relation',
                'next_of_kin_id',
                'next_of_kin_phone',
            ]);
        });
    }
}
