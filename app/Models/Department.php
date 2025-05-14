<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'hod',
        'created_by',
    ];

     public function head()
    {
        return $this->belongsTo(User::class, 'hod');
    }


    /**
     * The user who is the head of this department.
     */
    public function headOfDepartment()
    {
        return $this->belongsTo(User::class, 'hod');
    }

    /**
     * The user who created the department.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Users that belong to this department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

