<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parcel_id");
            $table->string("report");

            $table->foreign("parcel_id")->references("id")->on("item_pick_ups");
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
        Schema::dropIfExists('parcel_histories');
    }
}
