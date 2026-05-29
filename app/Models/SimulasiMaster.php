<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimulasiKecacatan;
use App\Models\SimulasiKelengkapan;

class SimulasiMaster extends Model
{
    protected $table = 'simulasi_master';

    protected $fillable = [
        'kategori', 'persen_min', 'persen_max',
        'keterangan', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'persen_min' => 'decimal:2',
        'persen_max' => 'decimal:2',
    ];

    public function kecacatan()
    {
        return $this->hasMany(SimulasiKecacatan::class, 'kategori', 'kategori');
    }

    public function kelengkapan()
    {
        return $this->hasMany(SimulasiKelengkapan::class, 'kategori', 'kategori');
    }
}