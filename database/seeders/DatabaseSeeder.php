<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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
        ]);
    }
}
