<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->bigInteger('transactionable_id')->unsigned()->nullable();
            $table->string('transactionable_type')->nullable();
            $table->decimal('debit', 25, 5)->nullable();
            $table->decimal('credit', 25, 5)->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
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
        Schema::table('student_wallet_transactions', function(Blueprint $table)
        {
            $table->dropForeign('student_wallet_transactions_student_id_foreign');
        });

        Schema::dropIfExists('student_wallet_transactions');
    }
}
