<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Status;
use App\Models\Etapa;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarefa>
 */
class TarefaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $etapas = Etapa::all()->pluck('id');
        $status = Status::all()->pluck('id');
        return [
            'titulo' => fake('pt_BR')->address(),
            'descricao' => fake('pt_BR')->sentence(),
            'etapa_id' => fake()->randomElement($etapas),
            'status_id' => fake()->randomElement($status),
            'data_inicio' => fake()->date(),
            'data_fim' => fake()->optional()->date(),
            'data_fim_previsto' => fake()->optional()->date(),
            'orcamento' => fake()->randomNumber(4),
            'andamento' => fake()->randomNumber(2, true),
            'cor' => fake()->hexColor()
        ];
    }
}
