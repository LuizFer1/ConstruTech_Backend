<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtapaRequest extends FormRequest
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
            'obra_id' => 'required',
            'data_inicio' => 'date|required',
            'data_fim' => 'date',
            'data_fim_previsto' => 'date'
        ];
    }
}
