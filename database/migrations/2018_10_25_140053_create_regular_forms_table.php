<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegularFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_regular_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('agt')->nullable();
            $table->string('ref')->nullable();
            $table->decimal('cash',14,2)->default(0);
            $table->decimal('bank',14,2)->default(0);
            $table->decimal('eviivo',14,2)->default(0);
            $table->decimal('parking_cash',14,2)->default(0);
            $table->decimal('parking_card',14,2)->default(0);
            $table->decimal('other_sales',14,2)->default(0);
	        $table->decimal('return',14,2)->default(0);
	        $table->decimal('advance_sales',14,2)->default(0);
            $table->string('remark')->nullable();
            $table->timestamp('date');


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
        Schema::dropIfExists('amsl_regular_forms');
    }
}
