<?php

// app/Models/Currency.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'symbol', 'currency', 'rate',
        'status', 'created_by', 'code', 'value',
    ];
}
