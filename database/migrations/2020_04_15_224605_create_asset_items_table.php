<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('asset_category_id')->unsigned()->nullable();
            $table->foreign('asset_category_id')->references('id')->on('asset_categories')->onDelete('cascade');
            $table->string('serial_number')->nullable();
            $table->string('model_number')->nullable();
            $table->integer('price')->default(0);
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
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
        Schema::table('asset_items', function(Blueprint $table)
        {
            $table->dropForeign('asset_items_asset_category_id_foreign');
        });
        
        Schema::dropIfExists('asset_items');
    }
}
