<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bunga extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_setting_bunga';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'bungas';

    public $timestamps = false;

    protected $fillable = [
        'persentase_bunga',
        'deskripsi',
    ];

    protected $casts = [
        'persentase_bunga' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_setting_bunga) {
                $model->id_setting_bunga = (string) Str::uuid();
            }
            if (!$model->created_at) {
                $model->created_at = now();
            }
        });
    }
}
