<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_code',
        'reg_name',
    ];
}
