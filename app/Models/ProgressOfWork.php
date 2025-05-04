<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressOfWork extends Model
{
    // Define a tabela associada ao modelo
    protected $table = 'midia_andamento_obra';

    protected $fillable = [
        'id',
        'data_do_registro',
        'id_obra',
        'id_responsavel',
        'tempo_climatico',
        'tempo_climatico_t_max',
        'tempo_climatico_t_min',
        'tempo_climatico_observacao',
        'servico_executado',
        'etapa_frente',
        'atrasos',
        'visitas_tecnicas',
        'acidente',
        'problemas_operacionais',
        'descricao'
    ];

    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra');
    }

    public function colaborador()
{
    return $this->belongsTo(User::class, 'id_responsavel');
}



    public static function findAll(array $query = [], string $userId)
    {
    
        $page = $query['page'] ?? 0;
        $limit = $query['limit'] ?? 10;
    
        $builder = self::query()->with(['obra', 'colaborador']);

        $filters = [
            'tipo_de_midia' => fn($value) => $builder->where('tipo_de_midia', $value),
            'arquivo_da_midia' => fn($value) => $builder->where('arquivo_da_midia', $value),
            'data_do_registro' => fn($value) => $builder->whereDate('data_do_registro', $value),
            'local_da_obra' => fn($value) => $builder->where('local_da_obra', $value),
            'responsavel_pelo_envio' => fn($value) => $builder->where('responsavel_pelo_envio', $value),
            'id_obra' => fn($value) => $builder->where('id_obra', $value),
            'descricao' => fn($value) => $builder->where('descricao', 'like', '%' . $value . '%'), 
            'orderBy' => fn($value) => $builder->orderBy($value),
        ];
    
        foreach ($filters as $key => $apply) {
            if (!empty($query[$key])) {
                $apply($query[$key]);
            }
        }
    
        $total = (clone $builder)
        ->whereHas('obra', function ($query) use ($userId) {
            $query->where('construtor_id', $userId);
        })
        ->count();
        
        $data = $builder
        ->offset($page * $limit)
        ->limit($limit)
        ->whereHas('obra', function ($query) use ($userId) {
            $query->where('construtor_id', $userId);
        })
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
        $item = self::with('obra')->where('id', $id)->first();
        return $item ? $item : [];
    }

    public function createRecord(array $data)
    {
        return self::create($data);

    }
    public function updateRecord(array $data)
    {
        
        $id = $data['id'] ?? null;
    
        if (!$id) {
            return ['error' => 'ID não informado.'];
        }
    
        $record = self::query()->where('id', $id)->first();
    
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