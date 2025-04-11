<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = 'colaboradores';
    
    protected $fillable = [
        'nome_completo', 'apelido', 'cpf', 'cargo', 'setor', 'vinculo', 'matricula',
        'data_admissao', 'email', 'telefone', 'cep', 'municipio', 'endereco', 'uf', 'complemento'
    ];
    
}
