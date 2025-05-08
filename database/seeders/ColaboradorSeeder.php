<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colaborador;

class ColaboradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run()
{
    Colaborador::create([
        'nome_completo' => 'João da Silva',
        'user_id' => 1,
        'apelido' => 'Joãozinho',
        'cpf' => '123.456.789-00',
        'cargo' => 'Analista',
        'setor' => 'TI',
        'vinculo' => 'CLT',
        'matricula' => '2025001',
        'data_admissao' => '2024-01-10',
        'email' => 'joao@empresa.com',
        'telefone' => '(88) 99999-9999',
        'cep' => '63000-000',
        'municipio' => 'Juazeiro do Norte',
        'endereco' => 'Rua Exemplo, 123',
        'uf' => 'CE',
        'complemento' => 'Apto 101',
    ]);
}

}
