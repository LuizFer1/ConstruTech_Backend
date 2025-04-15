<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'type' => 'admin',
            ]
        );

        $this->call([
            ColaboradorSeeder::class,
            'type' => 1,
            "password" => Hash::make("12345678")
        ]);
        User::factory(20)->create();

        $this->call([
            StatusSeeder::class,
            ClienteSeeder::class,
            ObraSeeder::class
        ]);

    }
}
