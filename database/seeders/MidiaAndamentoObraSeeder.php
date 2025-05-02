<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MidiaAndamentoObraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'tipo_de_midia' => ['imagem', 'video'][array_rand(['imagem', 'video'])],
                'arquivo_da_midia' => 'arquivo_' . $i . '.jpg',
                'data_do_registro' => now()->subDays(rand(1, 30)),
                'descricao' => 'Descrição do andamento da obra ' . $i,
                'local_da_obra' => 'Local ' . $i,
                'responsavel_pelo_envio' =>
                 'Responsável ' . $i,
                'id_obra' => rand(1), // Certifique-se de que os IDs de obras existam no banco
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('midia_andamento_obra')->insert($data);
    }
}