<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('city',50);
            $table->string('district',50);
            $table->string('phone_number',50)->nullable();
            $table->text('address')->nullable();
            $table->string('user_photo')->default("media\\\profiles\\\photos\\\default.png");
            $table->enum('state',['active','passive'])->default('active');
            $table->enum('type',['user','admin'])->default('user');
            $table->timestamps();
            $table->timestamp('last_login')->nullable();
            
        });
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
};
