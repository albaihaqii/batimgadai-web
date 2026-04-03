<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Emas & Perhiasan',
                'deskripsi' => 'Cincin, kalung, gelang, anting, dan emas batangan',
                'status' => 'active'
            ],
            [
                'nama' => 'Kendaraan',
                'deskripsi' => 'Motor, mobil, atau kendaraan lainnya dengan BPKB',
                'status' => 'active'
            ],
            [
                'nama' => 'Elektronik',
                'deskripsi' => 'Televisi, kulkas, mesin cuci dan perangkat elektronik lainnya',
                'status' => 'active'
            ],
            [
                'nama' => 'Gadget',
                'deskripsi' => 'Smartphone, tablet, dan perangkat mobile lainnya',
                'status' => 'active'
            ],
            [
                'nama' => 'Laptop & Komputer',
                'deskripsi' => 'Laptop, PC, dan perangkat komputer',
                'status' => 'active'
            ],
            [
                'nama' => 'Kamera',
                'deskripsi' => 'Kamera DSLR, mirrorless, dan aksesorisnya',
                'status' => 'active'
            ],
            [
                'nama' => 'Jam Tangan',
                'deskripsi' => 'Jam tangan branded atau bernilai tinggi',
                'status' => 'active'
            ],
            [
                'nama' => 'Alat Musik',
                'deskripsi' => 'Gitar, keyboard, drum, dan alat musik lainnya',
                'status' => 'active'
            ],
            [
                'nama' => 'Barang Koleksi',
                'deskripsi' => 'Barang koleksi bernilai seperti koin langka atau barang antik',
                'status' => 'active'
            ],
            [
                'nama' => 'Alat Rumah Tangga',
                'deskripsi' => 'Peralatan rumah tangga bernilai ekonomi',
                'status' => 'inactive'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}