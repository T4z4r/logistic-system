<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'line_manager_id',
        'position_id',
        'status',
        'mode',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    // Define relationships if necessary
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lineManager()
    {
        return $this->belongsTo(User::class, 'line_manager_id');
    }

      public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function managedEmployees()
    {
        return $this->hasMany(User::class, 'line_manager_id');
    }
}
