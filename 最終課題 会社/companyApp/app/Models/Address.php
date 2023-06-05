<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'billing',
        'billing_ruby',
        'address',
        'phone_number',
        'department',
        'to',
        'to_ruby'
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
