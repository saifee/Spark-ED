<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_employee_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->index()->unsigned();
            $table->foreign('employee_id')->references('id')->on('amsl_employees')->onDelete('cascade');
            $table->timestamp('start_date')->index();
            $table->timestamp('end_date')->nullable()->index();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('amsl_employee_histories');
    }
}
