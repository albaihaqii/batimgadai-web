<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perpanjangan extends Model
{
    protected $table = 'perpanjangan';

    protected $fillable = [
        'gadai_id', 'nasabah_id', 'officer_id', 'no_sbg',
        'nilai_pinjaman', 'jasa_persen', 'jasa_nominal',
        'denda_persen', 'denda_nominal', 'hari_terlambat',
        'total_bayar', 'tgl_perpanjangan', 'tgl_jt_lama', 'tgl_jt_baru',
        'status_bayar', 'metode_bayar',
        'midtrans_order_id', 'midtrans_token', 'midtrans_url', 'midtrans_response',
    ];

    protected $casts = [
        'tgl_perpanjangan'  => 'date',
        'tgl_jt_lama'       => 'date',
        'tgl_jt_baru'       => 'date',
        'nilai_pinjaman'    => 'decimal:2',
        'jasa_nominal'      => 'decimal:2',
        'denda_nominal'     => 'decimal:2',
        'total_bayar'       => 'decimal:2',
        'midtrans_response' => 'array',
    ];

    public function gadai()      { return $this->belongsTo(Gadai::class); }
    public function nasabah()    { return $this->belongsTo(Customer::class, 'nasabah_id'); }
    public function officer()    { return $this->belongsTo(User::class, 'officer_id'); }
}