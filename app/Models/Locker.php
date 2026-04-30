<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $table = 'loker';

    protected $fillable = [
        'kode_loker',
        'cabang_id',
        'rak',
        'status',
        'gadai_id',
        'keterangan',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function gadai()
    {
        return $this->belongsTo(Gadai::class, 'gadai_id');
    }

    // Generate kode loker otomatis
    public static function generateKode(string $kodeCabang, string $rak): string
    {
        $prefix = strtoupper($kodeCabang) . '-' . strtoupper($rak) . '-';
        $last   = self::where('kode_loker', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 3, '0', STR_PAD_LEFT);
    }
}