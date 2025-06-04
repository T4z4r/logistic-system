<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'stock_group_id', 'unit_id', 'opening_stock', 'rate'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stockGroup()
    {
        return $this->belongsTo(StockGroup::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function godownStocks()
    {
        return $this->hasMany(GodownStock::class);
    }
}
