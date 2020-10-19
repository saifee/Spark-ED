<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amsl_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->enum('account_type',['Fixed Asset','Current Asset','Current Asset-AR','Long-term Liabilities','Short-term Liabilities','Liabilities-AP','Expense','Income','Owner Equity']);
            $table->string('description')->nullable()->index();
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
        Schema::dropIfExists('amsl_accounts');
    }
}
