<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['superadmin', 'admin', 'officer']),
            'remember_token' => Str::random(10),
        ];
    }

    public function superadmin(): static
    {
        return $this->state(fn() => [
            'role' => 'superadmin',
            'password' => Hash::make('superadmin123'),
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn() => [
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }

    public function officer(): static
    {
        return $this->state(fn() => [
            'role' => 'officer',
            'password' => Hash::make('officer123'),
        ]);
    }
}
