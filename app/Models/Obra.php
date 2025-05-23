<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Obra extends Model
{
    /** @use HasFactory<\Database\Factories\ObraFactory> */
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($obra) {
            //Se foi setado status, não coloca como pendente
            if ($obra->status_id) {
                return;
            }
            $statusPendente = Status::where('nome', 'Pendente')->first();
            $obra->status_id = $statusPendente->id;
        });
    }

    protected $fillable = [
        'nome',
        'responsavel_id',
        'construtor_id',
        'cliente_id',
        'data_inicio',
        'data_fim_previsto',
        'data_fim',
        'data_arquivamento',
        'orcamento'
    ];

    protected $with = [
        'responsavel',
        'construtor',
        'cliente',
        'status'
    ];

    public function construtor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function etapas(): HasMany
    {
        return $this->hasMany(Etapa::class);
    }

    public function tarefas(): HasManyThrough
    {
        return $this->hasManyThrough(Tarefa::class, Etapa::class);
    }

    public function calculateAndamento()
    {
        $total = $this->etapas->count();
        $andamento = 0;

        if ($total > 0) {
            $concluidas = $this->etapas()
                ->whereHas('status', function ($query) {
                    $query->where('nome', 'Concluída');
                })
                ->count();

            $andamento = ($concluidas / $total) * 100;
        }
        $this->andamento = $andamento;
        if($andamento == 100){
            $statusConcluida = Status::where('nome', 'Concluída');
            $this->status()->associate($statusConcluida);
        }
        $this->save();

    }
}
