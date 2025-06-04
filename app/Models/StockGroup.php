<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'company_id', 'parent_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(StockGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(StockGroup::class, 'parent_id');
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'stock_group_id');
    }
}
