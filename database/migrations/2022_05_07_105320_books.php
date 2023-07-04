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
        Schema::create('books', function (Blueprint $table) {
            $table->id("book_id");
            $table->string("book_name");
            $table->string("author");
            $table->string("publisher");
            $table->date("publication_date")->nullable();
            $table->string("isbn")->unique();
            $table->text("image")->default("media\\\books\\\default.jpg");
            $table->enum('state',['active','passive'])->default('active');
            $table->int('demand',8)->default(0);
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
        Schema::dropIfExists('books');
    }
};
