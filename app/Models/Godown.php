<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'address'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
