<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('ref')->index();
            $table->decimal('amount',14,2)->index();
            $table->float('tax_rate',10,2)->nullable();
            $table->decimal('tax_amount',14,2)->nullable();
            $table->decimal('after_tax_amount',14,2)->nullable();
            $table->enum('payment_type',['Cash','Bank','Accounts Payable ','Prepaid Expense','Depreciation Fund']);
            $table->string('modelable_type')->nullable()->index();
            $table->integer('modelable_id')->nullable()->index();
            $table->integer('employee_id')->nullable()->index();
            $table->integer('asset_id')->nullable()->index();
            $table->string('description')->nullable();
            $table->timestamp('expense_date')->index();
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
        Schema::dropIfExists('amsl_expenses');
    }
}
