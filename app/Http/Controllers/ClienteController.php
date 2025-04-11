<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Symfony\Component\HttpFoundation\Response;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Cliente::paginate(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        $data = $request->all();
        $cliente = Cliente::create($data);
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
