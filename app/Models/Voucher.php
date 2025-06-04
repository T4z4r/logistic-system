<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'voucher_type_id', 'voucher_number', 'date', 'narration', 'amount', 'currency_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function voucherType()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function entries()
    {
        return $this->hasMany(VoucherEntry::class);
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }
}

