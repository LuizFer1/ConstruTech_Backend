<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarefaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'min:10',
            'descricao' => 'min:20',
            'etapa_id' => 'integer',
            'status_id' => 'integer',
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'data_fim_previsto' => 'date',
            'orcamento' => 'min:0|integer',
            'andamento' => 'min:0|max:100',
            'cor' => 'color'
        ];
    }
}
