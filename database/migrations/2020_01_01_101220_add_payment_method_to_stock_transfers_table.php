<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->decimal('cash_paid',25,5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'cash_paid',
            ]);
        });
    }
}
