<?php

namespace App\Models;

use App\Models\Truck;
use App\Models\AllocationCost;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocationCostPayment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    public function allocation()
    {
        return $this->belongsTo(AllocationCost::class, 'cost_id','id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id','id');
    }

    // For Auditible
    protected $auditInclude = ['cost_id', 'payment_date', 'payment_amount', 'created_by', 'updated_by'];
}
