<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'prefix', 'numbering'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
