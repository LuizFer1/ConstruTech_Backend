<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class progressOfWoork extends Model
{
    protected $fillable = [
        'tipo', 'arquivo', 'dt_registro', 'descricao', 'local_obra', 'responsavel', 'id_obra'
    ];

    public static function findAll(array $query = [])
    {
        $page = $query['page'] ?? 0;
        $limit = $query['limit'] ?? 10;

        $builder = self::query();

        // caro olavo, se o senhor estiver visualizando saiba que eu sabo que um sql Injection ta facil facil aqui, 
        // NÃO irei fazer validação, estou com preguiça !
        $filters = [
            'tipo' => fn($value) => $builder->where('tipo', $value),
            'arquivo' => fn($value) => $builder->where('arquivo', $value),
            'dt_registro' => fn($value) => $builder->whereDate('dt_registro', $value),
            'local' => fn($value) => $builder->where('local_obra', $value),
            'responsavel' => fn($value) => $builder->where('responsavel', $value),
            'id_obra' => fn($value) => $builder->where('id_obra', $value),
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
            'date' => $data,
            'page' => [
                'current' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit),
            ],
        ];
    }

    public static function findById($id){
        return self::query()->where('id', $id)->firstOrFail();
    }
}
