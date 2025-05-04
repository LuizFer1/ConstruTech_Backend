<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Status;
use App\Models\Obra;
use App\Models\Colaborador;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etapa>
 */
class EtapaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $obras = Obra::all()->pluck('id');
        $status = Status::all()->pluck('id');
        $colaboradores = Colaborador::all()->pluck('id');
        return [
            'nome' => fake('pt_BR')->address,
            'responsavel_id' => fake()->randomElement($colaboradores),
            'obra_id' => fake()->randomElement($obras),
            'status_id' => fake()->randomElement($status),
            'andamento' => fake()->randomNumber(2, true),
            'data_inicio' => fake()->date(),
            'data_fim' => fake()->optional()->date(),
            'data_fim_previsto' => fake()->optional()->date(),
            'orcamento' => fake()->randomNumber(5),
        ];
    }
}
