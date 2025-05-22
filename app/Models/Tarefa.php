<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    /** @use HasFactory<\Database\Factories\TarefaFactory> */
    use HasFactory;
    use SoftDeletes;


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($etapa) {
            if ($etapa->status_id) {
                return;
            }
            $statusPendente = Status::where('nome', 'Pendente')->first();
            $etapa->status_id = $statusPendente->id;
        });
    }

    protected $fillable = [
        'titulo',
        'descricao',
        'etapa_id',
        'status_id',
        'data_inicio',
        'data_fim',
        'data_fim_previsto',
        'orcamento',
        'andamento',
        'cor'
    ];

    protected $with = [
        'status',
        'colaboradores'
    ];



    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class);
    }

    public function colaboradores(): BelongsToMany
    {
        return $this->belongsToMany(Colaborador::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
