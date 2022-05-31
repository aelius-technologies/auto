<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_receipt', function (Blueprint $table) {
            $table->id();
            $table->string('obf_id')->nullable();
            $table->string('cash')->nullable();
            $table->enum('spcial_case', ['yes', 'no'])->default('no');
            $table->enum('status', ['accepted', 'rejected','pending'])->default('pending');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_receipt');
    }
}
