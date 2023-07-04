<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersTrades extends Model
{
    use HasFactory;
    protected $table = 'users_trades';
    protected $primaryKey = 'id';
    protected $fillable = [
        "trade_number",
        "user_id",
        "state",
    ];
}
