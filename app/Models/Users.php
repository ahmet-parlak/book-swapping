<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Users extends Authenticatable
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        "email", 
        "password", 
        "first_name", 
        "last_name", 
        "city", 
        "district", 
        "phone_number",
        "address", 
        "state",
        "user_photo",
        "created_at", 
        "updated_at",
        "last_login"
    ];

}
