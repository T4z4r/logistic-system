<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'symbol', 'is_compound', 'base_unit_id', 'conversion_factor'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }
}
