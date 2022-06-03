<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObfTable extends Migration{
    public function up(){
        Schema::create('obf', function (Blueprint $table) {
            $table->id();
            $table->string('temporary_id')->nullable();
            $table->string('booking_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('gst')->nullable();
            $table->text('address')->nullable();
            $table->string('registration')->nullable();
            $table->string('email')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_image')->nullable();
            $table->string('adhar_number')->nullable();
            $table->string('adhar_image')->nullable();
            $table->string('licance_number')->nullable();
            $table->string('licance_image')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('dob')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_reletion')->nullable();
            $table->string('nominee_age')->nullable();
            $table->string('occupation')->nullable();
            $table->string('sales_person_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('varient_id')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('ex_showroom_price')->nullable();
            $table->string('registration_tax_id')->nullable();
            $table->string('insurance_id')->nullable();
            $table->string('municipal_tax_id')->nullable();
            $table->string('tcs_tax_id')->nullable();
            $table->string('accessory_id')->nullable();
            $table->string('extanded_warranty_id')->nullable();
            $table->string('fasttag_id')->nullable();
            $table->string('trad_in_value')->nullable();
            $table->string('on_road_price')->nullable();
            $table->string('on_road_price_word')->nullable();
            $table->string('finance_id')->nullable();
            $table->string('finance_branch_id')->nullable();
            $table->string('lead_id')->nullable();
            $table->string('booking_amount')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'accepted', 'obf_accepted', 'account_accepted', 'obf_rejected', 'account_rejected', 'rejected', 'deleted', 'completed'])->default('pending');
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    public function down(){
        Schema::dropIfExists('obf');
    }
}
