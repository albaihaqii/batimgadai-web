<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $smg = DB::table('cabang')->where('kode', 'SMG')->value('id');
        $mgl = DB::table('cabang')->where('kode', 'MGL')->value('id');
        $krm = DB::table('cabang')->where('kode', 'KRM')->value('id');

        DB::table('users')->insert([

            [
                'nama'       => 'Bapak Direktur',
                'email'      => 'superadmin@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'superadmin',
                'cabang_id'  => null,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama'       => 'Faiq Raihan',
                'email'      => 'admin.semanggi@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Abdillah Aziz',
                'email'      => 'admin.mangli@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $mgl,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Juliana Intan',
                'email'      => 'admin.karimata@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $krm,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama'       => 'Yohanes Fabian',
                'email'      => 'officer1@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Alviansyah Nurhidayat',
                'email'      => 'officer2@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $mgl,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}