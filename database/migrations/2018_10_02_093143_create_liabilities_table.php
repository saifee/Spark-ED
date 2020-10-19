<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_liabilities', function (Blueprint $table) {
            $table->increments('id');
//            $table->integer('account_id')->index()->unsigned();
//            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('accountable_type');
            $table->integer('accountable_id');
            $table->string('ref')->index();
            $table->string('transaction_type')->index();
            $table->enum('payment_type',['Cash','Bank','Accounts Receivable','Owner Equity'])->index();
            $table->decimal('amount',14,2)->default(0);
            $table->string('description')->nullable();
            $table->integer('asset_id')->nullable()->index();
            $table->timestamp('liability_date')->index();
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
        Schema::dropIfExists('amsl_liabilities');
    }
}
