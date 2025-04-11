<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObraRequest extends FormRequest
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
            'nome' => 'required|min:10',
            'responsavel_id' => 'required',
            'cliente_id' => 'required',
            'status_id' => 'integer',
            'andamento' => 'min:0|max:100',
            'data_inicio' => 'date',
            'data_fim_previsto' => 'date',
            'data_fim' => 'date',
            'data_arquivamento' => 'date',
            'orcamento' => 'min:0|integer'
        ];
    }
}
