<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'tipe_penerima',
        'penerima_id',
        'judul',
        'pesan',
        'tipe_notif',
        'referensi_tipe',
        'referensi_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relasi ke user (jika tipe_penerima = user)
    public function user()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    // Relasi ke nasabah (jika tipe_penerima = nasabah)
    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'penerima_id');
    }

    // Helper: kirim notifikasi ke user
    public static function kirimKeUser(int $userId, string $judul, string $pesan, string $tipeNotif, string $referensiTipe = null, int $referensiId = null): void
    {
        self::create([
            'tipe_penerima'  => 'user',
            'penerima_id'    => $userId,
            'judul'          => $judul,
            'pesan'          => $pesan,
            'tipe_notif'     => $tipeNotif,
            'referensi_tipe' => $referensiTipe,
            'referensi_id'   => $referensiId,
            'is_read'        => 0,
        ]);
    }

    // Helper: kirim notifikasi ke nasabah
    public static function kirimKeNasabah(int $nasabahId, string $judul, string $pesan, string $tipeNotif, string $referensiTipe = null, int $referensiId = null): void
    {
        self::create([
            'tipe_penerima'  => 'nasabah',
            'penerima_id'    => $nasabahId,
            'judul'          => $judul,
            'pesan'          => $pesan,
            'tipe_notif'     => $tipeNotif,
            'referensi_tipe' => $referensiTipe,
            'referensi_id'   => $referensiId,
            'is_read'        => 0,
        ]);
    }
}