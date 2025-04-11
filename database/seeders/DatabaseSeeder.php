<?php

namespace Database\Seeders;

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


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
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
