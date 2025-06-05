<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ColaboradorController extends Controller
{
    private User $user;
    public function __construct()
    {
        $this->user = auth('api')->user();
    }
    function isValidCpf($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colaboradores = Colaborador::where('user_id', $this->user->id)->get();
        return response()->json($colaboradores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_completo' => 'required|string',
            'cpf' => [
                'required',
                'string',
                'unique:colaboradores',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidCpf($value)) {
                        $fail('O CPF informado é inválido.');
                    }
                },
            ],
            'email' => 'required|email|unique:colaboradores',
            'matricula' => 'required|unique:colaboradores',
            'data_admissao' => 'required|date',
            'cargo' => 'required|string',
            'setor' => 'required|string',
            'vinculo' => 'required|string',
            'telefone' => 'nullable|string',
            'cep' => 'required|string',
            'municipio' => 'required|string',
            'endereco' => 'required|string',
            'uf' => 'required|string|size:2',
            'complemento' => 'nullable|string',
            'apelido' => 'nullable|string',
        ]);
        $validated['user_id'] = $this->user->id;
        $colaborador = Colaborador::create($validated);

        return response()->json([
            'message' => 'Colaborador cadastrado com sucesso',
            'colaborador' => $colaborador
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json(['message' => 'Colaborador não encontrado'], 404);
        }

        return response()->json($colaborador);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json(['message' => 'Colaborador não encontrado'], 404);
        }

        $colaborador->update($request->all());

        return response()->json([
            'message' => 'Colaborador atualizado com sucesso',
            'colaborador' => $colaborador
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $colaborador = Colaborador::find($id);

        if (!$colaborador) {
            return response()->json(['message' => 'Colaborador não encontrado'], 404);
        }

        // Verifica se há tarefas associadas
        if ($colaborador->tarefas()->count() > 0) {
            return response()->json([
                'error' => 'Não é possível excluir: existem tarefas associadas a este colaborador.'
            ], 409);
        }

        $colaborador->delete();

        return response()->json(['message' => 'Colaborador deletado com sucesso']);
    }

}