<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'nasabah_id',
        'nama_barang',
        'kategori',
        'merk',
        'tipe_model',
        'kondisi',
        'kelengkapan',
        'foto',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Customer::class, 'nasabah_id');
    }

    public function gadai()
    {
        return $this->hasMany(Gadai::class, 'barang_id');
    }

    public function fotos()
    {
        return $this->hasMany(BarangFoto::class)->orderBy('urutan');
    }

    public function fotoUtama()
    {
        return $this->hasOne(BarangFoto::class)->orderBy('urutan');
    }
}