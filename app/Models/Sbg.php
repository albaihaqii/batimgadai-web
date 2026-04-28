<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sbg extends Model
{
    protected $table = 'sbg';

    protected $fillable = [
        'no_sbg',
        'nasabah_id',
        'gadai_id',
        'tipe',
        'referensi_id',
        'tgl_transaksi',
        'qr_token',
    ];

    protected $casts = [
        'tgl_transaksi' => 'date',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'nasabah_id');
    }

    public function gadai()
    {
        return $this->belongsTo(Gadai::class, 'gadai_id');
    }

    // Generate QR Token UUID
    public static function generateQrToken(): string
    {
        do {
            $token = Str::uuid()->toString();
        } while (self::where('qr_token', $token)->exists());

        return $token;
    }
}