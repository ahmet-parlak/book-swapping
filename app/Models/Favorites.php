<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    use HasFactory;
    protected $table = "favorites";
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id",
        "book_id"
    ];
}
