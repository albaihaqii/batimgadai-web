<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JasaRate extends Model
{
    protected $table = 'jasa_rates';

    protected $fillable = [
        'tipe',
        'min_pinjaman',
        'max_pinjaman',
        'jasa_15_hari',
        'jasa_30_hari',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'jasa_15_hari' => 'float',
        'jasa_30_hari' => 'float',
    ];

    // Cari rate berdasarkan nilai pinjaman dan tipe
    public static function getRate(int $nilaiPinjaman, string $tipe = 'umum'): ?self
    {
        return self::where('tipe', $tipe)
            ->where('is_active', true)
            ->where('min_pinjaman', '<=', $nilaiPinjaman)
            ->where(function ($q) use ($nilaiPinjaman) {
                $q->whereNull('max_pinjaman')
                  ->orWhere('max_pinjaman', '>=', $nilaiPinjaman);
            })
            ->first();
    }
}