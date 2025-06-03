<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ClienteController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Cliente::where('user_id',$this->user->id)->paginate(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {

        $data = $request->all();
        $cliente = new Cliente();
        $cliente->fill($data);
        $cliente->user()->associate($this->user);
        $cliente->save();
        return response()->json($cliente, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return response()->json($cliente, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $data = $request->all();
        $cliente->update($data);
        $cliente->save();
        $cliente->refresh();
        return response()->json($cliente, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json($cliente, Response::HTTP_OK);
    }
}
