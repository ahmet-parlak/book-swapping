<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Users::insert([
            "email" => "info@johndoe.com",
            "password" => Hash::make("123456"),
            "first_name" => "John",
            "last_name" => "Doe",
            "city" => "İstanbul",
            "district" => "Tuzla"
        ]);
        Users::insert([
            "email" => "admin@app.com",
            "password" => Hash::make("123456"),
            "first_name" => "John",
            "last_name" => "Doe",
            "city" => "İstanbul",
            "district" => "Kadıköy",
            "type" => "admin"
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
