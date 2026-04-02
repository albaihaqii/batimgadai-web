<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'no_telp',
        'maps_url',
        'status',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'cabang_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }
}