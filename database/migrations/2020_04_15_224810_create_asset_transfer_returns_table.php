<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTransferReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_transfer_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_transfer_id')->unsigned()->nullable();
            $table->foreign('asset_transfer_id')->references('id')->on('asset_transfers')->onDelete('cascade');
            $table->bigInteger('asset_item_id')->unsigned()->nullable();
            $table->foreign('asset_item_id')->references('id')->on('asset_items')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->decimal('quantity',25,5)->nullable()->default(0);
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('options')->nullable();
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
        Schema::table('asset_transfer_returns', function(Blueprint $table)
        {
            $table->dropForeign('asset_transfer_returns_asset_transfer_id_foreign');
            $table->dropForeign('asset_transfer_returns_asset_item_id_foreign');
            $table->dropForeign('asset_transfer_returns_user_id_foreign');
        });
        
        Schema::dropIfExists('asset_transfer_returns');
    }
}
