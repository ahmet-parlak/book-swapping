<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trades extends Model
{
    use HasFactory;
    protected $table = 'trades';
    protected $primaryKey = 'id';
    protected $fillable = [
        "trade_number",
        "state",
    ];
}
