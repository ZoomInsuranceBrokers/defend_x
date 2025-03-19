<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'company_id',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'photo',
        'email',
        'password',
        'mobile',
        'dob',
        'created_on',
        'created_by',
        'is_active',
        'is_delete',
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
     * Define relationship with the Role model.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Define relationship with the Company model.
     */
    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    /**
     * Define relationship with the User model for created_by.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
