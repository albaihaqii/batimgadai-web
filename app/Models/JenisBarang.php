<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JenisBarang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jenis_barang';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'jenis_barang';

    protected $fillable = [
        'id_categories',
        'nama_jenis',
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_jenis_barang) {
                $model->id_jenis_barang = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship: Jenis Barang belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_categories', 'id_categories');
    }
}
