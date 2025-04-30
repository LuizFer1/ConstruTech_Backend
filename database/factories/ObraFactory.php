<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Status;
use App\Models\Cliente;
use App\Models\Colaborador;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Obra>
 */
class ObraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $users = User::all()->pluck('id');
        $clientes = Cliente::all()->pluck('id');
        $status = Status::all()->pluck('id');
        $colaboradores = Colaborador::all()->pluck('id');

        return [
            'nome' => fake('pt_BR')->company,
            'construtor_id' => fake()->randomElement($users),
            'responsavel_id' => fake()->randomElement($colaboradores),
            'cliente_id' => fake()->randomElement($clientes),
            'status_id' => fake()->randomElement($status),
            'andamento' => fake()->randomNumber(2, true),
            'data_inicio' => fake()->optional()->date(),
            'data_fim_previsto' => fake()->optional()->date(),
            'data_fim' => fake()->optional()->date(),
            'data_arquivamento' => fake()->optional()->date(),
            'orcamento' => fake()->randomNumber(7)
        ];
    }
}
