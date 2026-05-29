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
        $mst = DB::table('cabang')->where('kode', 'MST')->value('id');
        $krm = DB::table('cabang')->where('kode', 'KRM')->value('id');

        DB::table('users')->insert([
            // Admin (Pimpinan Cabang)
            [
                'nama'       => 'Hartono Wibisono',
                'email'      => 'admin.semanggi@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Rudianto Prasetyo',
                'email'      => 'admin.mastrip@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $mst,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Sugiarto Handoko',
                'email'      => 'admin.karimata@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'cabang_id'  => $krm,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Petugas Semanggi (3 orang)
            [
                'nama'       => 'Dimas Arya Saputra',
                'email'      => 'officer.smg1@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Rizal Maulana Akbar',
                'email'      => 'officer.smg2@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Fajar Nugroho',
                'email'      => 'officer.smg3@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $smg,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Petugas Mastrip
            [
                'nama'       => 'Wahyu Eko Santoso',
                'email'      => 'officer.mst1@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $mst,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Andika Pratama',
                'email'      => 'officer.mst2@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $mst,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Bagas Setiawan',
                'email'      => 'officer.mst3@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $mst,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Petugas Karimata
            [
                'nama'       => 'Kevin Dwi Cahyono',
                'email'      => 'officer.krm1@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $krm,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Reza Firmansyah',
                'email'      => 'officer.krm2@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $krm,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Gilang Ramadhan',
                'email'      => 'officer.krm3@batimgadai.com',
                'password'   => Hash::make('password123'),
                'role'       => 'officer',
                'cabang_id'  => $krm,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}