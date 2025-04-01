<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorMaster extends Model
{
    use HasFactory;

    protected $table = 'vendor_master'; // Explicitly specifying the table name

    protected $fillable = [
        'vendor_name',
        'is_active',
        'support_email',
        'support_mobile',
        'logo',
        'address'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
