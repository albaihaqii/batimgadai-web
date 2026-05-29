<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'kode', 'nama', 'alamat',
        'latitude', 'longitude',
        'hari_buka', 'jam_buka', 'jam_tutup',
        'no_telp', 'maps_url', 'status',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
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