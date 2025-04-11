<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    private $status = [
        ['nome' => 'Pendente', 'descricao' => 'Ainda não iniciado, pendente de início.'],
        ['nome' => 'Em Andamento', 'descricao' => 'Já iniciado, mas ainda em andamento, sem conclusão.'],
        ['nome' => 'Concluída', 'descricao' => 'Fase de execução já concluída.'],
        ['nome' => 'Arquivada', 'descricao' => 'Finalizou todas as fases, já tendo a documentação sido registrada.']
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->status as $status){
            Status::create($status);
        }
    }
}
