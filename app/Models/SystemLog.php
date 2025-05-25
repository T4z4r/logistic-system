<?php

// app/Models/SystemLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = ['process_name', 'user_id', 'narration'];
}
