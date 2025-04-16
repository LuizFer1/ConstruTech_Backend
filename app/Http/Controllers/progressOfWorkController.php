<?php

namespace App\Http\Controllers;

use App\Models\ProgressOfWork;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProgressOfWorkController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->only([
            'tipo', 'arquivo', 'dt_registro',
            'local_obra', 'responsavel', 'id_obra', 'orderBy'
        ]);

        $query['page'] = $request->query('page', 0);
        $query['limit'] = $request->query('limit', 10);

        return response()->json(ProgressOfWork::findAll($query));
    }

    public function show($id)
    {
        $item = ProgressOfWork::find($id);

        if (!$item) {
            return response()->json(['error' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($item);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|string',
            'arquivo' => 'nullable|string',
            'dt_registro' => 'required|date',
            'local_obra' => 'required|string',
            'responsavel' => 'required|string',
            'id_obra' => 'required|integer|exists:obras,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item = ProgressOfWork::create($request->all());

        return response()->json($item, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $item = ProgressOfWork::find($id);

        if (!$item) {
            return response()->json(['error' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'sometimes|required|string',
            'arquivo' => 'nullable|string',
            'dt_registro' => 'sometimes|required|date',
            'local_obra' => 'sometimes|required|string',
            'responsavel' => 'sometimes|required|string',
            'id_obra' => 'sometimes|required|integer|exists:obras,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item->update($request->all());

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ProgressOfWork::find($id);

        if (!$item) {
            return response()->json(['error' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $item->delete();

        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
