<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMaster extends Model
{
    use HasFactory;

    protected $table = 'company_master';

    protected $primaryKey = 'comp_id';

    public $timestamps = true;

    protected $fillable = [
        'comp_name',
        'business_type',
        'comp_addr',
        'comp_city',
        'comp_state',
        'comp_pincode',
        'file_dir',
        'comp_icon_url',
        'status',
        'is_approved',
        'updated_by',
        'created_by',
    ];
}
