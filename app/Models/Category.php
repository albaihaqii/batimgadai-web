<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_categories';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'categories';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_categories) {
                $model->id_categories = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship: Category has many Jenis Barang
     */
    public function jenisBarang()
    {
        return $this->hasMany(JenisBarang::class, 'id_categories', 'id_categories');
    }
}
