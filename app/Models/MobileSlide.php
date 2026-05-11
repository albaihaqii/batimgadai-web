<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileSlide extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function activeOrDefaults()
    {
        $slides = self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return $slides->isNotEmpty() ? $slides : collect(self::defaults());
    }

    public static function defaults(): array
    {
        return [
            (object) [
                'title' => 'Beranda Aplikasi',
                'description' => 'Tampilan utama aplikasi mobile BATIM GADAI.',
                'image_path' => 'frontend/images/mockup-1.png',
                'sort_order' => 1,
                'is_active' => true,
            ],
            (object) [
                'title' => 'Simulasi Gadai',
                'description' => 'Nasabah dapat melihat estimasi pinjaman sebelum datang ke outlet.',
                'image_path' => 'frontend/images/mockup-2.png',
                'sort_order' => 2,
                'is_active' => true,
            ],
            (object) [
                'title' => 'Riwayat Gadai',
                'description' => 'Status dan riwayat transaksi gadai dapat dipantau dari aplikasi.',
                'image_path' => 'frontend/images/mockup-3.png',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
    }
}
