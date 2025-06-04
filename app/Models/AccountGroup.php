<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'company_id', 'type'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(AccountGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountGroup::class, 'parent_id');
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'group_id');
    }
}
