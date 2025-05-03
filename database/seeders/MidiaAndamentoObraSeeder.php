<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Obra;

class MidiaAndamentoObraSeeder extends Seeder
{
    private static array $obras;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        self::$obras = Obra::all()->pluck('id')->toArray();


        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'tipo_de_midia' => ['Foto', 'Video'][array_rand(['Foto', 'Video'])],
                'arquivo_da_midia' => 'arquivo_' . $i . '.jpg',
                'data_do_registro' => now()->subDays(rand(1, 30)),
                'descricao' => 'Descrição do andamento da obra ' . $i,
                'local_da_obra' => 'Local ' . $i,
                'responsavel_pelo_envio' =>
                 'Responsável ' . $i,
                'id_obra' => fake()->randomElement(self::$obras), // Certifique-se de que os IDs de obras existam no banco
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('midia_andamento_obra')->insert($data);
    }
}
