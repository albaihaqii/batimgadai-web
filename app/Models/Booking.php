<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'no_booking', 'nasabah_id', 'cabang_id',
        'tgl_kunjungan', 'jam_kunjungan', 'keperluan',
        'catatan_nasabah', 'catatan_admin', 'status',
        'diproses_oleh', 'tgl_diproses',
        'kategori_barang', 'harga_pasar',
        'estimasi_min', 'estimasi_max',
    ];

    protected $casts = [
        'tgl_kunjungan' => 'date',
        'tgl_diproses'  => 'datetime',
    ];

    public static function generateNoBooking(): string
    {
        $prefix = 'BK' . now()->format('ym');
        $last   = self::where('no_booking', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
    }

    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'nasabah_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}