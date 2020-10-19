<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnerequitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_ownerequities', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('account',['Capital','Withdraw']);
            $table->string('ref')->index();
            $table->string('transaction_type')->index();
            $table->enum('payment_type',['Cash','Bank','Accounts Receivable'])->index();
            $table->decimal('amount',14,2)->default(0);
            $table->string('description')->nullable();
            $table->integer('liability_id')->nullable()->index();
            $table->timestamp('equity_date')->index();
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
        Schema::dropIfExists('amsl_ownerequities');
    }
}
