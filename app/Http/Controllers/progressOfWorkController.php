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
        $query = [
            'tipo_de_midia' => $request->query('tipo_de_midia'),
            'arquivo_da_midia' => $request->query('arquivo_da_midia'),
            'data_do_registro' => $request->query('data_do_registro'),
            'local_da_obra' => $request->query('local_da_obra'),
            'descricao' => $request->query('descricao'),
            'responsavel_pelo_envio' => $request->query('responsavel_pelo_envio'),
            'id_obra' => $request->query('id_obra'),
            'orderBy' => $request->query('orderBy'),
            'page' => $request->query('page', 0),
            'limit' => $request->query('limit', 10),
        ];

        
        $result = ProgressOfWork::findAll($query);
        // dd($result);      
    
        return response()->json($result);
    }

    public function show($id)
    {
        $item = ProgressOfWork::findById($id);

        if (!$item) {
            return response()->json(['error' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($item);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_de_midia' => 'required|string',
            'arquivo_da_midia' => 'nullable|string',
            'data_do_registro' => 'required|date',
            'descricao' => 'nullable|string',
            'local_da_obra' => 'required|string',
            'responsavel_pelo_envio' => 'required|string',
            'id_obra' => 'required|integer|exists:obras,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item = (new ProgressOfWork)->createRecord($request->all());

        return response()->json($item, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo_de_midia' => 'sometimes|required|string',
            'arquivo_da_midia' => 'nullable|string',
            'data_do_registro' => 'sometimes|required|date',
            'local_da_obra' => 'sometimes|required|string',
            'descricao' => 'sometimes|nullable|string',
            'responsavel_pelo_envio' => 'sometimes|required|string',
            'id_obra' => 'sometimes|required|integer|exists:obras,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    
        $data = $request->all();
        $data['id_midia_andamento_obra'] = $id;

        $item = (new ProgressOfWork)->updateRecord($data);
    
        return response()->json($item, Response::HTTP_OK);

    }

    public function destroy($id)
    {
        try {
            $item = (new ProgressOfWork())->deleteRecord($id);
    
            if (isset($item['error'])) {
                return response()->json($item, Response::HTTP_NOT_FOUND);
            }
    
            return response()->json(['message' => 'Registro deletado com sucesso.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao deletar o registro.',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
