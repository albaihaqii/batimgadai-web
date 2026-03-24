<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'no_cif',
        'nama',
        'no_ktp',
        'no_hp',
        'alamat',
        'cabang_id',
        'status',
        'tgl_bergabung',
        'created_by',
    ];

    protected $casts = [
        'tgl_bergabung' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'cabang_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}