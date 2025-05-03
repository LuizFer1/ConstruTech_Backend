<?php

namespace App\Http\Controllers;

use App\Models\ProgressOfWork;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Obra;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProgressOfWorkController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function index(Request $request)
    {
        $query = [
            'data_do_registro' => $request->query('tipo_de_midia'),
            'id_obra' => $request->query('arquivo_da_midia'),
            'id_responsavel' => $request->query('data_do_registro'),
            'descricao' => $request->query('descricao'),
            'id_obra' => $request->query('id_obra'),
            'orderBy' => $request->query('orderBy'),
            'page' => $request->query('page', 0),
            'limit' => $request->query('limit', 10),
        ];
        
        $result = ProgressOfWork::findAll($query, $this->user->id); 
    
        return response()->json($result);
    }

    public function show($id)
    {
        $item = ProgressOfWork::findById($id);

        if (!$item) {
            return response()->json(['error' => 'Registro não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        if($item['obra']['construtor_id'] != $this->user->id) {
            return response()->json(['error' => 'Acesso negado.'], Response::HTTP_FORBIDDEN);
        }

        return response()->json($item);
    }
    // ok
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data_do_registro' => 'required|date',
            'id_obra' => 'required|integer|exists:obras,id',
            'id_responsavel' => 'required|integer|exists:colaboradores,id',
            'descricao' => 'nullable|string',
            'tempo_climatico' => 'nullable|string',
            'tempo_climatico_t_max' => 'nullable|string',
            'tempo_climatico_t_min' => 'nullable|string',
            'tempo_climatico_observacao' => 'nullable|string',
            'servico_executado' => 'required|string',
            'etapa_frente' => 'required|string',
            'atrasos' => 'nullable|string',
            'visitas_tecnicas' => 'nullable|string',
            'acidente' => 'nullable|string',
            'problemas_operacionais' => 'nullable|string',
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
            'data_do_registro' => 'required|date',
            'id_obra' => 'required|integer|exists:obras,id',
            'id_responsavel' => 'required|integer|exists:colaboradores,id',
            'descricao' => 'nullable|string',
            'tempo_climatico' => 'nullable|string',
            'tempo_climatico_t_max' => 'nullable|integer',
            'tempo_climatico_t_min' => 'nullable|integer',
            'tempo_climatico_observacao' => 'nullable|string',
            'servico_executado' => 'required|string',
            'etapa_frente' => 'required|string',
            'atrasos' => 'nullable|string',
            'visitas_tecnicas' => 'nullable|string',
            'acidente' => 'nullable|string',
            'problemas_operacionais' => 'nullable|string',
        ]);
    
         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
         }
    
         $data = $request->all();
         $data['id'] = $id;

         $obra = Obra::find($data['id_obra']);

         if (!$obra) {
             return response()->json(['error' => 'Obra não encontrada.'], Response::HTTP_NOT_FOUND);
         }
         
         if($obra['construtor_id'] != $this->user->id) {
             return response()->json(['error' => 'Acesso negado.'], Response::HTTP_FORBIDDEN);
         }
  
        $item = (new ProgressOfWork)->updateRecord($data);

        return response()->json($item);
    
        return response()->json([
            'message' => 'Relatório atualizado com sucesso',
            'data' => $item,   
        ] ,Response::HTTP_OK);

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
