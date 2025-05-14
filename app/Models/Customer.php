<?php

// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'contact_person', 'TIN', 'VRN', 'phone', 'address',
        'email', 'company', 'abbreviation', 'created_by',
        'status', 'credit_term',
    ];
}
