<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateitempickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_pick_ups', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('email');
            $table->string('collection_address');
            $table->string('postal_code');
            $table->string('package_type');
            $table->string('commodity_type');
            $table->string('collection_date');
            $table->string('collection_time');
            $table->string('item_description');
            $table->string('estimated_weight');
            $table->string('number_of_boxes');
            $table->string('receiver_first_name');
            $table->string('receiver_last_name');
            $table->string('receiver_address');
            $table->string('receiver_city');
            $table->string('receiver_state');
            $table->string('receiver_phone_number');
            $table->string('receiver_phone_number2');
            $table->string('delivery_type');
            $table->string('payment_type');
            $table->string('tracking_id');
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('item_pick_ups');
    }
}
