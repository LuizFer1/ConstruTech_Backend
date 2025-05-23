<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Obra;
use App\Http\Requests\UpdateObraRequest;
use App\Http\Requests\StoreObraRequest;
use App\Models\Status;

class ObraController extends Controller
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
        $query = Obra::query()->where('construtor_id', $this->user->id);

        if ($request->has('status')) {
            $query->whereHas('status', function ($q) use ($request) {
                $q->where('nome', 'like', $request->get('status'));
            });
        }

        return response()->json($query->paginate(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreObraRequest $request)
    {
        $data = $request->all();
        $data['construtor_id'] = $this->user->id;
        $obra = Obra::create($data);
        return response()->json($obra, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Obra $obra)
    {
        if($obra->construtor_id != $this->user->id){
            return response()->json(['message' => 'Você não tem permissão para visualizar essa obra'], Response::HTTP_FORBIDDEN);
        }
        $obra->load(['etapas']);
        return response()->json($obra, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateObraRequest $request, Obra $obra)
    {
        if ($obra->contrutor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para editar esta obra.'], Response::HTTP_FORBIDDEN);
        };
        $data = $request->all();
        $obra->update($data);
        $obra->save();
        $obra->refresh();
        return response()->json($obra, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obra $obra)
    {
        if ($obra->contrutor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para apagar esta obra.'], Response::HTTP_FORBIDDEN);
        };
        $obra->delete();
        return response()->json($obra, Response::HTTP_OK);
    }

    public function arquivar(Obra $obra){
        if ($obra->contrutor_id != $this->user->id) {
            return response()->json(['message' => 'Você não tem permissão para alterar esta obra.'], Response::HTTP_FORBIDDEN);
        };
        $statusConcluida = Status::whereLike('nome', 'Concluída')->get()->first();
        if($obra->status->id != $statusConcluida->id){
            return response()->json(['message' => 'Você não pode arquivar uma obra que não está concluída'], Response::HTTP_BAD_REQUEST);
        }
        $statusArquivada = Status::whereLike('nome', 'Arquivada')->get()->first();
        $obra->status()->associate($statusArquivada);
        $obra->save();
    }
}
