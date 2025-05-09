<?php

namespace Database\Factories;

use App\Models\User;
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

        $users = User::all()->pluck('id')->toArray();
        return [
            'nome' => fake('pt_BR')->name(),
            'user_id' => fake()->randomElement($users),
            'cpf_cnpj' => fake('pt_BR')->unique()->cpf(),
            'endereco' => fake('pt_BR')->address(),
            'telefone' => fake('pt_BR')->phoneNumber(),
            'municipio' => fake('pt_BR')->city(),
            'data_nascimento' => fake()->dateTime()
        ];
    }
}
