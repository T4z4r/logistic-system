<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'cost_category_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function costCategory()
    {
        return $this->belongsTo(CostCategory::class);
    }
}
