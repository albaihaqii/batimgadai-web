<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulasiKecacatan extends Model
{
    protected $table = 'simulasi_kecacatan';

    protected $fillable = [
        'kategori', 'label', 'faktor', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'faktor'    => 'decimal:2',
    ];
}