<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GodownStock extends Model
{
    use HasFactory;

    protected $fillable = ['stock_item_id', 'godown_id', 'quantity', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }
}
