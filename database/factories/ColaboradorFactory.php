<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ColaboradorFactory extends Factory
{
    public function definition(): array
{
    return [
        'nome_completo' => $this->faker->name,
        'apelido' => $this->faker->firstName,
        'cpf' => $this->generateValidCpf(), // <-- usa função abaixo
        'email' => $this->faker->unique()->safeEmail,
        'matricula' => $this->faker->unique()->numerify('MAT###'),
        'data_admissao' => '2024-01-01',
        'cargo' => 'Desenvolvedor',
        'setor' => 'TI',
        'vinculo' => 'CLT',
        'telefone' => '88999999999',
        'cep' => '63000000',
        'municipio' => 'Juazeiro do Norte',
        'endereco' => 'Rua Exemplo',
        'uf' => 'CE',
        'complemento' => 'Sala 01',
    ];
}

    private function generateValidCpf(): string
        {
            $n1 = rand(0, 9);
            $n2 = rand(0, 9);
            $n3 = rand(0, 9);
            $n4 = rand(0, 9);
            $n5 = rand(0, 9);
            $n6 = rand(0, 9);
            $n7 = rand(0, 9);
            $n8 = rand(0, 9);
            $n9 = rand(0, 9);

            $d1 = $n9*2 + $n8*3 + $n7*4 + $n6*5 + $n5*6 + $n4*7 + $n3*8 + $n2*9 + $n1*10;
            $d1 = 11 - ($d1 % 11);
            if ($d1 >= 10) $d1 = 0;

            $d2 = $d1*2 + $n9*3 + $n8*4 + $n7*5 + $n6*6 + $n5*7 + $n4*8 + $n3*9 + $n2*10 + $n1*11;
            $d2 = 11 - ($d2 % 11);
            if ($d2 >= 10) $d2 = 0;

            return "$n1$n2$n3$n4$n5$n6$n7$n8$n9$d1$d2";
        }

}
