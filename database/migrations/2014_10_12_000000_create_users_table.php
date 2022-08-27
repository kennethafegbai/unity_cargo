<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("firstname");
            $table->string("lastname");
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string("address")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            //$table->string('isStaff')->default(false);
            $table->string('role')->default('client');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'firstname'=> 'admin',
            'lastname'=> 'admin',
            'phone_number'=>'08023456789',
            'role'=>'admin',
            'email'=>'admin@gmail.com',
            'address'=>'34, Allen av, lagos',
            'password'=>Hash::make('password'),
            'created_at'=>\Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'=>\Carbon\Carbon::now()->toDateTimeString(),
        ]); 
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
