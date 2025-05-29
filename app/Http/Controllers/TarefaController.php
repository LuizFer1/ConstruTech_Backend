<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tarefa;
use App\Models\Etapa;
use App\Http\Requests\UpdateTarefaRequest;
use App\Http\Requests\StoreTarefaRequest;
use App\Models\Status;

class TarefaController extends Controller
{

    private User $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $etapa_id = $request->get('etapa_id');
        if (!$etapa_id) {
            return response()->json(['message' => 'Etapa não informada.'], Response::HTTP_BAD_REQUEST);
        }
        $etapa = Etapa::findOrFail($etapa_id);
        $obra = $etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para acessar tarefas desta obra.'], Response::HTTP_FORBIDDEN);
        }

        $tarefas = Tarefa::query()->where('etapa_id', $etapa_id)->get();
        return response()->json($tarefas, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTarefaRequest $request)
    {
        $data = $request->all();
        $statusAndamento = Status::whereLike('nome', 'Em Andamento')->get()->first();
        $etapa = Etapa::findOrFail($data['etapa_id']);
        $colaboradoresId = $data['colaboradores'] ?? [];
        if (count($colaboradoresId) > 0) {
            $existentes = $etapa->obra->colaboradores()->pluck('id');
            if (count($existentes) == 0) {
                return response()->json(['message' => 'Obra não possui colaboradores.'], Response::HTTP_BAD_REQUEST);
            }
            foreach ($colaboradoresId as $colaboradorId) {
                if (!$existentes->contains($colaboradorId)) {
                    return response()->json(['message' => "Colaborador id $colaboradorId não pertence a obra."], Response::HTTP_BAD_REQUEST);
                }
            }
        }

        if ($etapa->obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para criar tarefas nesta etapa.'], Response::HTTP_FORBIDDEN);
        }
        $tarefa = Tarefa::create($data);
        $tarefa->colaboradores()->sync($colaboradoresId);
        $etapa->status()->associate($statusAndamento);
        $etapa->obra->status()->associate($statusAndamento);
        return response()->json($tarefa, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        return response()->json($tarefa);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTarefaRequest $request, Tarefa $tarefa)
    {
        $obra = $tarefa->etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para editar tarefas desta obra.'], Response::HTTP_FORBIDDEN);
        }
        $colaboradoresId = $data['colaboradores'] ?? [];
        if (count($colaboradoresId) > 0) {
            $existentes = $obra->colaboradores()->pluck('id');
            if (count($existentes) == 0) {
                return response()->json(['message' => 'Obra não possui colaboradores.'], Response::HTTP_BAD_REQUEST);
            }
            foreach ($colaboradoresId as $colaboradorId) {
                if (!$existentes->contains($colaboradorId)) {
                    return response()->json(['message' => "Colaborador id $colaboradorId não pertence a obra."], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        $data = $request->all();
        $tarefa->update($data);
        $tarefa->colaboradores()->sync($colaboradoresId);
        $tarefa->save();
        $tarefa->refresh();
        return response()->json($tarefa, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        $obra = $tarefa->etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para deletar esta tarefa, ela não partence a uma obra sua.'], Response::HTTP_FORBIDDEN);
        }
        $tarefa->delete();
        return response()->json($tarefa, Response::HTTP_OK);
    }

    public function iniciarTarefa(Tarefa $tarefa)
    {
        $obra = $tarefa->etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para alterar esta tarefa.'], Response::HTTP_FORBIDDEN);
        }

        $statusAndamento = Status::where('nome', 'Em Andamento')->first();
        $tarefa->status()->associate($statusAndamento);
        $tarefa->etapa->status()->associate($statusAndamento);
        $tarefa->etapa->obra->status()->associate($statusAndamento);
        if ($tarefa->etapa->obra->data_inicio == null) {
            $tarefa->etapa->obra->data_inicio = now();
        }
        $tarefa->etapa->obra->save();
        if ($tarefa->etapa->data_inicio == null) {
            $tarefa->etapa->data_inicio = now();
        }
        $tarefa->etapa->save();
        $tarefa->save();

        // Atualizar andamento da etapa
        $tarefa->etapa->calculateAndamento();

        return response()->json($tarefa, Response::HTTP_OK);
    }

    public function concluirTarefa(Tarefa $tarefa)
    {
        $obra = $tarefa->etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para alterar esta tarefa.'], Response::HTTP_FORBIDDEN);
        }

        $statusConcluido = Status::where('nome', 'Concluída')->first();
        $tarefa->status()->associate($statusConcluido);
        $tarefa->save();

        // Atualizar andamento da etapa
        $tarefa->etapa->calculateAndamento();

        return response()->json($tarefa, Response::HTTP_OK);
    }
}
