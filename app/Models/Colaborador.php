<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colaborador extends Model
{
    use HasFactory; 
    protected $table = 'colaboradores';

    protected $fillable = [
        'nome_completo', 'apelido', 'cpf', 'cargo', 'setor', 'vinculo', 'matricula',
        'data_admissao', 'email', 'telefone', 'cep', 'municipio', 'endereco', 'uf', 'complemento', 'user_id'
    ];

    public function tarefas(): BelongsToMany
    {
        return $this->belongsToMany(Tarefa::class);
    }

    public function etapas(): BelongsToMany
    {
        return $this->belongsToMany(Etapa::class);
    }

}
