<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test; // Usando Attributes para PHPUnit 10+

class ColaboradorTest extends TestCase
{
    use RefreshDatabase; // Garante um banco de dados limpo para cada teste

    protected $user; // Propriedade para armazenar o usuário autenticado

    /**
     * Configuração inicial para cada teste.
     * Cria e autentica um usuário.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Cria um usuário para autenticação em todos os testes de Colaborador
        // Assume que um 'type' padrão (e.g., 1 para 'Empresa') é suficiente para criar colaboradores.
        $this->user = User::factory()->create(['type' => 1]);
    }

    // --- TESTES DE CRUD BÁSICOS (JÁ EXISTENTES E AJUSTADOS) ---

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

        // Autentica o usuário antes de fazer a requisição
        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(201) // Espera status 201 Created
                 ->assertJsonFragment(['nome_completo' => 'João Silva']);

        // Verifica se o colaborador foi criado no banco de dados, incluindo o user_id
        $this->assertDatabaseHas('colaboradores', [
            'cpf' => '12345678909',
            'user_id' => $this->user->id
        ]);
    }

    #[Test]
    public function pode_listar_colaboradores()
    {
        // Cria 3 colaboradores associados ao usuário autenticado
        Colaborador::factory()->count(3)->create(['user_id' => $this->user->id]);
        // Cria 1 colaborador para outro usuário (não deve ser listado para $this->user)
        Colaborador::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($this->user, 'api')->getJson('/api/colaboradores');

        $response->assertStatus(200)
                 ->assertJsonCount(3); // Deve listar apenas os 3 criados para o usuário logado
    }

    #[Test]
    public function pode_mostrar_um_colaborador()
    {
        // Cria um colaborador associado ao usuário autenticado
        $colaborador = Colaborador::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->getJson("/api/colaboradores/{$colaborador->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $colaborador->id, 'nome_completo' => $colaborador->nome_completo]);
    }

    #[Test]
    public function pode_atualizar_um_colaborador()
    {
        // Cria um colaborador associado ao usuário autenticado
        $colaborador = Colaborador::factory()->create(['user_id' => $this->user->id]);

        $novosDados = [
            'nome_completo' => 'Atualizado Nome',
            'cargo' => 'Gerente de Projeto',
            'telefone' => '99999888888' // Adiciona outros campos para atualização
        ];

        $response = $this->actingAs($this->user, 'api')->putJson("/api/colaboradores/{$colaborador->id}", $novosDados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nome_completo' => 'Atualizado Nome']);

        $this->assertDatabaseHas('colaboradores', [
            'id' => $colaborador->id,
            'nome_completo' => 'Atualizado Nome',
            'cargo' => 'Gerente de Projeto'
        ]);
    }

    #[Test]
    public function pode_deletar_um_colaborador()
    {
        // Cria um colaborador associado ao usuário autenticado
        $colaborador = Colaborador::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->deleteJson("/api/colaboradores/{$colaborador->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Colaborador deletado com sucesso']);

        // Verifica se o colaborador foi removido do banco de dados
        $this->assertDatabaseMissing('colaboradores', ['id' => $colaborador->id]);
    }

    // --- TESTES DE VALIDAÇÃO ---

    #[Test]
    public function nao_pode_criar_colaborador_sem_nome_completo()
    {
        // Usa a factory para gerar dados válidos e sobrescreve o campo a ser testado
        $data = Colaborador::factory()->make(['nome_completo' => null, 'user_id' => $this->user->id])->toArray();

        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(422) // Espera status 422 Unprocessable Entity
                 ->assertJsonValidationErrors('nome_completo'); // Verifica erro específico no campo
    }

    #[Test]
    public function nao_pode_criar_colaborador_com_cpf_invalido()
    {
        $data = Colaborador::factory()->make(['cpf' => '123', 'user_id' => $this->user->id])->toArray(); // CPF obviamente inválido

        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('cpf');
    }

    #[Test]
    public function nao_pode_criar_colaborador_com_email_duplicado()
    {
        // Cria um colaborador com um email que já existe
        Colaborador::factory()->create(['email' => 'teste@example.com', 'user_id' => $this->user->id]);

        $data = Colaborador::factory()->make(['email' => 'teste@example.com', 'user_id' => $this->user->id])->toArray();

        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }

    #[Test]
    public function nao_pode_criar_colaborador_com_matricula_duplicada()
    {
        Colaborador::factory()->create(['matricula' => 'MAT001', 'user_id' => $this->user->id]);

        $data = Colaborador::factory()->make(['matricula' => 'MAT001', 'user_id' => $this->user->id])->toArray();

        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('matricula');
    }

    #[Test]
    public function nao_pode_criar_colaborador_com_data_admissao_invalida_formato()
    {
        $data = Colaborador::factory()->make(['data_admissao' => 'invalido', 'user_id' => $this->user->id])->toArray();

        $response = $this->actingAs($this->user, 'api')->postJson('/api/colaboradores', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('data_admissao');
    }

    // --- TESTES DE AUTORIZAÇÃO ---

    #[Test]
    public function usuario_nao_pode_visualizar_colaborador_de_outro_usuario()
    {
        $outroUser = User::factory()->create();
        $colaboradorOutroUser = Colaborador::factory()->create(['user_id' => $outroUser->id]);

        $response = $this->actingAs($this->user, 'api')->getJson("/api/colaboradores/{$colaboradorOutroUser->id}");

        $response->assertStatus(404); // Deve retornar 404 porque não encontrou o colaborador para este user_id
    }

    #[Test]
    public function usuario_nao_pode_atualizar_colaborador_de_outro_usuario()
    {
        $outroUser = User::factory()->create();
        $colaboradorOutroUser = Colaborador::factory()->create(['user_id' => $outroUser->id]);

        $novosDados = ['nome_completo' => 'Tentativa de Atualização'];

        $response = $this->actingAs($this->user, 'api')->putJson("/api/colaboradores/{$colaboradorOutroUser->id}", $novosDados);

        $response->assertStatus(404); // Deve retornar 404
        $this->assertDatabaseMissing('colaboradores', ['id' => $colaboradorOutroUser->id, 'nome_completo' => 'Tentativa de Atualização']);
    }

    #[Test]
    public function usuario_nao_pode_deletar_colaborador_de_outro_usuario()
    {
        $outroUser = User::factory()->create();
        $colaboradorOutroUser = Colaborador::factory()->create(['user_id' => $outroUser->id]);

        $response = $this->actingAs($this->user, 'api')->deleteJson("/api/colaboradores/{$colaboradorOutroUser->id}");

        $response->assertStatus(404); // Deve retornar 404
        $this->assertDatabaseHas('colaboradores', ['id' => $colaboradorOutroUser->id]); // Verifica que não foi deletado
    }

    // --- TESTES DE AUTENTICAÇÃO (INTEGRAÇÃO COM MIDDLEWARE) ---

    #[Test]
    public function acesso_negado_a_colaboradores_sem_autenticacao()
    {
        // Tenta acessar a rota sem autenticar nenhum usuário
        $response = $this->postJson('/api/colaboradores', []);

        $response->assertStatus(401) // Espera 401 Unauthorized
                 ->assertJson(['message' => 'Usuário não autenticado']);
    }

    #[Test]
    public function acesso_negado_a_listagem_colaboradores_sem_autenticacao()
    {
        $response = $this->getJson('/api/colaboradores');

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Usuário não autenticado']);
    }

}