<?php

// app/Models/OffBudgetCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffBudgetCategory extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'cost_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cost()
    {
        return $this->belongsTo(TruckCost::class, 'cost_id');
    }
}
