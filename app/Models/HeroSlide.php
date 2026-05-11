<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'highlighted_title',
        'description',
        'image_path',
        'primary_button_label',
        'primary_button_url',
        'secondary_button_label',
        'secondary_button_url',
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
                'title' => 'Gadai Cepat, Aman &',
                'highlighted_title' => 'Terpercaya',
                'description' => 'BATIM GADAI hadir sebagai solusi pinjaman dana cepat dengan sistem gadai barang yang aman, transparan, dan terpercaya. Proses mudah, pencairan cepat, dan barang jaminan tersimpan dengan aman di outlet kami yang telah berizin OJK.',
                'image_path' => 'frontend/images/hero-1.png',
                'primary_button_label' => 'Temukan Cabang',
                'primary_button_url' => '#cabang',
                'secondary_button_label' => 'Lihat Layanan',
                'secondary_button_url' => '#layanan',
                'sort_order' => 1,
                'is_active' => true,
            ],
            (object) [
                'title' => 'Kelola Gadai Lebih Mudah dengan',
                'highlighted_title' => 'Sistem Digital',
                'description' => 'Sistem Informasi Gadai Elektronik BATIM GADAI membantu pengelolaan data nasabah, transaksi gadai, perpanjangan, dan pelunasan secara terkomputerisasi. Seluruh proses tercatat rapi, akurat, dan dapat diakses kapan saja oleh tim kami.',
                'image_path' => 'frontend/images/hero-2.png',
                'primary_button_label' => 'Pelajari Sistem',
                'primary_button_url' => '#alur',
                'secondary_button_label' => null,
                'secondary_button_url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            (object) [
                'title' => 'Simulasi dan Booking',
                'highlighted_title' => 'Gadai dari Aplikasi',
                'description' => 'Nasabah dapat melakukan simulasi estimasi nilai pinjaman, booking kunjungan ke outlet, memantau status transaksi gadai, hingga mendapatkan notifikasi pengingat jatuh tempo langsung dari aplikasi mobile BATIM GADAI di genggaman Anda.',
                'image_path' => 'frontend/images/hero-3.png',
                'primary_button_label' => 'Preview Aplikasi',
                'primary_button_url' => '#aplikasi',
                'secondary_button_label' => null,
                'secondary_button_url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
    }
}
