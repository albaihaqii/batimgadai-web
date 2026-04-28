<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalGadai extends Model
{
    protected $table = 'approval_gadai';

    protected $fillable = [
        'gadai_id',
        'admin_id',
        'status',
        'nilai_final',
        'catatan',
        'tgl_diproses',
    ];

    protected $casts = [
        'tgl_diproses' => 'datetime',
        'nilai_final'  => 'decimal:2',
    ];

    public function gadai()
    {
        return $this->belongsTo(Gadai::class, 'gadai_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}