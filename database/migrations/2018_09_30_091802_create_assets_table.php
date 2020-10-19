<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index()->unsigned();
            $table->foreign('account_id')->references('id')->on('amsl_accounts')->onDelete('cascade');
            $table->string('ref')->index();
            $table->string('transaction_type')->index();
            $table->enum('payment_type',['Cash','Bank','Accounts Payable','Owner Equity','Adjust'])->index();
            $table->decimal('amount',14,2)->default(0);
            $table->string('description')->nullable();
            $table->integer('liability_id')->nullable()->index();
            $table->integer('expense_id')->nullable()->index();
            $table->timestamp('asset_date')->index();
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
        Schema::dropIfExists('amsl_assets');
    }
}
