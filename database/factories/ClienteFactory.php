<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake('pt_BR')->name(),
            'cpf_cnpj' => fake('pt_BR')->unique()->cpf(),
            'endereco' => fake('pt_BR')->address(),
            'telefone' => fake('pt_BR')->phoneNumber(),
            'municipio' => fake('pt_BR')->city(),
            'data_nascimento' => fake()->dateTime()
        ];
    }
}
