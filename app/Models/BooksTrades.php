<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksTrades extends Model
{
    use HasFactory;
    protected $table = 'books_trades';
    protected $primaryKey = 'id';
    protected $fillable = [
        "trade_number",
        "book_id",
        "user_id",
    ];
}
