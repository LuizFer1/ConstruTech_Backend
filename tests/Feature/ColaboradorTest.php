<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Colaborador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ColaboradorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_criar_um_colaborador()
    {
        $data = [
            'nome_completo' => 'João Silva',
            'cpf' => '12345678909', // CPF válido de teste
            'email' => 'joao@email.com',
            'matricula' => 'A123',
            'data_admissao' => '2023-01-01',
            'cargo' => 'Analista',
            'setor' => 'TI',
            'vinculo' => 'CLT',
            'telefone' => '88999999999',
            'cep' => '63000000',
            'municipio' => 'Juazeiro do Norte',
            'endereco' => 'Rua Exemplo',
            'uf' => 'CE',
            'complemento' => 'Apto 101',
            'apelido' => 'Joãozinho'
        ];

        $response = $this->postJson('/api/colaboradores', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nome_completo' => 'João Silva']);

        $this->assertDatabaseHas('colaboradores', ['cpf' => '12345678909']);
    }

    #[Test]
    public function pode_listar_colaboradores()
    {
        Colaborador::factory()->count(3)->create();

        $response = $this->getJson('/api/colaboradores');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function pode_mostrar_um_colaborador()
    {
        $colaborador = Colaborador::factory()->create();

        $response = $this->getJson("/api/colaboradores/{$colaborador->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $colaborador->id]);
    }

    #[Test]
    public function pode_atualizar_um_colaborador()
    {
        $colaborador = Colaborador::factory()->create();

        $novosDados = ['nome_completo' => 'Atualizado Nome'];

        $response = $this->putJson("/api/colaboradores/{$colaborador->id}", $novosDados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome_completo' => 'Atualizado Nome']);

        $this->assertDatabaseHas('colaboradores', ['id' => $colaborador->id, 'nome_completo' => 'Atualizado Nome']);
    }

    #[Test]
    public function pode_deletar_um_colaborador()
    {
        $colaborador = Colaborador::factory()->create();

        $response = $this->deleteJson("/api/colaboradores/{$colaborador->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Colaborador deletado com sucesso']);

        $this->assertDatabaseMissing('colaboradores', ['id' => $colaborador->id]);
    }
}
