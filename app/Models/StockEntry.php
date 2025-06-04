<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id', 'stock_item_id', 'godown_id', 'quantity', 'rate', 'company_id'
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);

    }
}
