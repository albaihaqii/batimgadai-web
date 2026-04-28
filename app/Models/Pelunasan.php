<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelunasan extends Model
{
    protected $table = 'pelunasan';

    protected $fillable = [
        'gadai_id', 'nasabah_id', 'officer_id', 'no_sbg',
        'nilai_pinjaman', 'jasa_persen', 'jasa_nominal',
        'denda_persen', 'denda_nominal', 'hari_terlambat',
        'total_tebus', 'tgl_pelunasan', 'tgl_jt',
        'status_bayar', 'metode_bayar',
        'midtrans_order_id', 'midtrans_token', 'midtrans_url', 'midtrans_response',
    ];

    protected $casts = [
        'tgl_pelunasan'     => 'date',
        'tgl_jt'            => 'date',
        'nilai_pinjaman'    => 'decimal:2',
        'jasa_nominal'      => 'decimal:2',
        'denda_nominal'     => 'decimal:2',
        'total_tebus'       => 'decimal:2',
        'midtrans_response' => 'array',
    ];

    public function gadai()      { return $this->belongsTo(Gadai::class); }
    public function nasabah()    { return $this->belongsTo(Customer::class, 'nasabah_id'); }
    public function officer()    { return $this->belongsTo(User::class, 'officer_id'); }
}