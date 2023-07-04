<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationChannels extends Model
{
    use HasFactory;
    protected $table = "notificationchannels";
    protected $primaryKey = "id";
    protected $fillable = [
        "user",
        "channel"
    ];
}
