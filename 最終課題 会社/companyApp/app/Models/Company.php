<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company',
        'company_ruby',
        'address',
        'phone_number',
        'ceo',
        'ceo_ruby'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($company) {
            // 関連する子テーブルのレコードも論理削除する
            $company->address()->delete();
        });
    }


    public function address()
    {
        return $this->hasMany(Address::class);
    }

}


