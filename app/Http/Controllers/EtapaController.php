<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Obra;
use App\Models\Etapa;
use App\Http\Requests\UpdateEtapaRequest;
use App\Http\Requests\StoreEtapaRequest;

class EtapaController extends Controller
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
        $obra_id = $request->get('obra_id');
        if (!$obra_id) {
            return response()->json(['message' => 'Obra não informada.'], Response::HTTP_BAD_REQUEST);
        }
        $obra = Obra::findOrFail($obra_id);
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para acessar etapas desta obra.'], Response::HTTP_FORBIDDEN);
        }
        $query = Etapa::query()->where('obra_id', $obra->id);
        return response()->json($query->paginate(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEtapaRequest $request)
    {
        $data = $request->all();
        $obra = Obra::findOrFail($data['obra_id']);
        if ($obra->construtor_id !== $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para criar etapas nesta obra.'], Response::HTTP_FORBIDDEN);
        }
        $etapa = Etapa::create($data);
        return response()->json($etapa, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Etapa $etapa)
    {
        $etapa->load(['tarefas']);
        return response()->json($etapa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEtapaRequest $request, Etapa $etapa)
    {
        $obra = $etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para editar etapas desta obra.'], Response::HTTP_FORBIDDEN);
        }
        $data = $request->all();
        $etapa->update($data);
        $etapa->save();
        $etapa->refresh();
        return response()->json($etapa, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etapa $etapa)
    {
        $obra = $etapa->obra;
        if ($obra->construtor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para deletar esta etapa, ela não pertence a uma obra sua.'], Response::HTTP_FORBIDDEN);
        }
        $etapa->delete();
        return response()->json($etapa, Response::HTTP_OK);
    }
}
