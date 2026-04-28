<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gadai extends Model
{
    protected $table = 'gadai';

    protected $fillable = [
        'no_sbg',
        'nasabah_id',
        'barang_id',
        'cabang_id',
        'loker_id',
        'officer_id',
        'admin_id',
        'nilai_taksiran_min',
        'nilai_taksiran_max',
        'nilai_taksiran_akhir',
        'nilai_pinjaman',
        'nilai_pinjaman_awal',
        'nilai_pinjaman_tambahan',
        'catatan_tambahan_pinjaman',
        'tipe_jasa',
        'jasa_persen',
        'jasa_nominal',
        'total_tebus',
        'tgl_gadai',
        'tgl_jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'tgl_gadai'            => 'date',
        'tgl_jatuh_tempo'      => 'date',
        'nilai_taksiran_min'   => 'decimal:2',
        'nilai_taksiran_max'   => 'decimal:2',
        'nilai_taksiran_akhir' => 'decimal:2',
        'nilai_pinjaman'       => 'decimal:2',
        'jasa_nominal'         => 'decimal:2',
        'total_tebus'          => 'decimal:2',
        'nilai_pinjaman_tambahan' => 'integer',
        'nilai_pinjaman_awal'     => 'integer',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'nasabah_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function loker()
    {
        return $this->belongsTo(Locker::class, 'loker_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function approval()
    {
        return $this->hasOne(ApprovalGadai::class, 'gadai_id');
    }

    public function sbg()
    {
        return $this->hasMany(Sbg::class, 'gadai_id');
    }

    public function perpanjangan()
    {
        return $this->hasMany(Perpanjangan::class);
    }

    public function pelunasan()
    {
        return $this->hasOne(Pelunasan::class);
    }

    // Generate No SBG
    public static function generateNoSbg(string $kodeCabang): string
    {
        $prefix = now()->format('ym') . strtoupper($kodeCabang);
        $last   = self::where('no_sbg', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
    }
}