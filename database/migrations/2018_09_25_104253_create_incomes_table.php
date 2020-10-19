<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('ref')->index();
            $table->decimal('amount',14,2)->index();
            $table->float('tax_rate',10,2)->nullable();
            $table->decimal('tax_amount',14,2)->nullable();
            $table->decimal('after_tax_amount',14,2)->nullable();
            $table->enum('payment_type',['Cash','Bank','Accounts Receivable']);
            $table->integer('asset_id')->nullable()->index();
            $table->string('description')->nullable();
            $table->timestamp('income_date')->index();
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
        Schema::dropIfExists('amsl_incomes');
    }
}
