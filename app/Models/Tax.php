<?php

// app/Models/Tax.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'status',
        'created_by'
    ];
}