<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etapa extends Model
{
    /** @use HasFactory<\Database\Factories\EtapaFactory> */
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
        'nome',
        'responsavel_id',
        'obra_id',
        'data_inicio',
        'data_fim',
        'data_fim_previsto'
    ];

    protected $with = [
        'responsavel',
        'status'
    ];

    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }

    public function calculateAndamento()
    {
        $total = $this->tarefas->count();
        $andamento = 0;

        if ($total > 0) {
            $concluidas = $this->tarefas()
                ->whereHas('status', function ($query) {
                    $query->where('nome', 'Concluída');
                })
                ->count();

            $andamento = ($concluidas / $total) * 100;
        }
        $this->andamento = $andamento;
        if ($andamento == 100) {
            $statusConcluida = Status::where('nome', 'Concluída')->get()->first();
            $this->status()->associate($statusConcluida);
        }
        $this->save();
        $this->obra->calculateAndamento();
    }
}
