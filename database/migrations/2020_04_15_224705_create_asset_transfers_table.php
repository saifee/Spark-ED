<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->date('return_due_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('return_status',20)->nullable();
            $table->text('description')->nullable();
            $table->text('return_description')->nullable();
            $table->text('options')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->uuid('upload_token')->nullable();
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
        Schema::table('asset_transfers', function(Blueprint $table)
        {
            $table->dropForeign('asset_transfers_employee_id_foreign');
            $table->dropForeign('asset_transfers_user_id_foreign');
        });

        Schema::dropIfExists('asset_transfers');
    }
}
