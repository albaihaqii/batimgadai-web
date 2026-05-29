<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulasiKelengkapan extends Model
{
    protected $table = 'simulasi_kelengkapan';

    protected $fillable = [
        'kategori', 'label', 'faktor', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'faktor'    => 'decimal:2',
    ];
}