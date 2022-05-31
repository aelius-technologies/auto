<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocation', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('billing_date')->nullable();
            $table->string('obf_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('dsa_or_broker')->nullable();
            $table->string('disb_amount')->nullable();
            $table->string('payment_due')->nullable();
            $table->string('fatd')->nullable();
            $table->string('iatd')->nullable();
            $table->string('tentetive_delivery_date')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'allocated','rejected'])->default('pending');
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
        Schema::dropIfExists('allocation');
    }
}
