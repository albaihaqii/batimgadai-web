<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangFoto extends Model
{
    protected $table = 'barang_fotos';

    protected $fillable = [
        'barang_id',
        'foto_path',
        'urutan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}