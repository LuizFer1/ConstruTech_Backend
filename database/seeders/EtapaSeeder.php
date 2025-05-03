<?php

namespace Database\Seeders;

use App\Models\Etapa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EtapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Etapa::factory(200)->create();
    }
}
