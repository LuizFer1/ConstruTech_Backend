<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressOfWork extends Model
{
    // Define a tabela associada ao modelo
    protected $table = 'midia_andamento_obra';

    protected $primaryKey = 'id_midia_andamento_obra'; // Exemplo: 'id_midia'

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'tipo_de_midia', 
        'arquivo_da_midia', 
        'data_do_registro', 
        'descricao', 
        'local_da_obra', 
        'responsavel_pelo_envio', 
        'id_obra'
    ];

    /**
     * Relacionamento com a tabela 'obras'.
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }

    /**
     * Método para buscar todos os registros com filtros e paginação.
     */

    public static function findAll(array $query = [])
    {
        $page = $query['page'] ?? 0;
        $limit = $query['limit'] ?? 10;
    
        $builder = self::query();
    
        // Aplicação de filtros
        $filters = [
            'tipo_de_midia' => fn($value) => $builder->where('tipo_de_midia', $value),
            'arquivo_da_midia' => fn($value) => $builder->where('arquivo_da_midia', $value),
            'data_do_registro' => fn($value) => $builder->whereDate('data_do_registro', $value),
            'local_da_obra' => fn($value) => $builder->where('local_da_obra', $value),
            'responsavel_pelo_envio' => fn($value) => $builder->where('responsavel_pelo_envio', $value),
            'id_obra' => fn($value) => $builder->where('id_obra', $value),
            'descricao' => fn($value) => $builder->where('descricao', 'like', '%' . $value . '%'), // Busca por texto que contém
            'orderBy' => fn($value) => $builder->orderBy($value),
        ];
    
        foreach ($filters as $key => $apply) {
            if (!empty($query[$key])) {
                $apply($query[$key]);
            }
        }
    
        $total = $builder->count();
    
        $data = $builder
            ->offset($page * $limit)
            ->limit($limit)
            ->get();
    
        return [
            'data' => $data,
            'page' => [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit),
            ],
        ];
    }

    /**
     * Método para buscar um registro pelo ID.
     */
    public static function findById($id)
    {
        $item = self::query()->where('id_midia_andamento_obra', $id)->first();
        return $item ? $item : [];
    }

    public function createRecord(array $data)
    {
        return self::create($data);

    }
    public function updateRecord(array $data)
    {
        $id = $data['id_midia_andamento_obra'] ?? null;
    
        if (!$id) {
            return ['error' => 'ID não informado.'];
        }
    
        $record = self::find($id);
    
        if (!$record) {
            return ['error' => 'Registro não encontrado.'];
        }
    
        // Atualiza apenas os campos permitidos
        $record->fill($data);
        $record->save();
    
        return [
            'message' => 'Registro atualizado com sucesso.',
            'data' => $record->toArray()
        ];
    }

    public function deleteRecord($id)
    {
        try {
            $record = self::find($id);
    
            if (!$record) {
                return ['error' => 'Registro não encontrado.'];
            }
    
            $record->delete();
    
            return ['message' => 'Registro removido com sucesso.'];
        } catch (\Exception $e) {
            return [
                'error' => 'Erro ao tentar remover o registro.',
                'message' => $e->getMessage(),
            ];
        }
    }
    
}