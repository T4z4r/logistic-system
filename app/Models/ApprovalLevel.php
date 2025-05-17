<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    protected $fillable = [
        'approval_id',
        'role_id',
        'level_name',
        'rank',
        'label_name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
