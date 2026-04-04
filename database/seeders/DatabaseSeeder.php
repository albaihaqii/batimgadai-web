<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->superadmin()->create([
        //     'nama' => 'Super Admin',
        //     'email' => 'superadmin@gmail.com',
        // ]);

        // User::factory()->admin()->create([
        //     'nama' => 'Admin Cabang',
        //     'email' => 'admin@gmail.com',
        // ]);

        // User::factory()->officer()->create([
        //     'nama' => 'Officer',
        //     'email' => 'officer@gmail.com',
        // ]);
        $this->call([
            CategorySeeder::class,
        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            LockerSeeder::class,
        ]);
    }
}