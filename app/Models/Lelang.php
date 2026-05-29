<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    protected $table = 'lelang';

    protected $fillable = [
        'gadai_id', 'nasabah_id', 'no_sbg',
        'tgl_jatuh_tempo', 'tgl_lelang',
        'sisa_hutang', 'harga_terjual', 'selisih',
        'status_selisih', 'keterangan', 'status',
        'diproses_oleh',
    ];

    protected $casts = [
        'tgl_jatuh_tempo' => 'date',
        'tgl_lelang'      => 'date',
        'sisa_hutang'     => 'decimal:2',
        'harga_terjual'   => 'decimal:2',
        'selisih'         => 'decimal:2',
    ];

    public function gadai()
    {
        return $this->belongsTo(Gadai::class, 'gadai_id');
    }

    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'nasabah_id');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}