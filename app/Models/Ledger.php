<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group_id', 'company_id', 'opening_balance', 'contact_details'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function group()
    {
        return $this->belongsTo(AccountGroup::class, 'group_id');
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
