<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'process_name',
        'levels',
        'escallation',
        'escallation_time',
    ];

    protected $casts = [
        'escallation' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function level()
    {
        return $this->hasMany(ApprovalLevel::class, 'approval_id');
    }
}