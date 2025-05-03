<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEtapaRequest extends FormRequest
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
            'nome' => 'min:10',
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'data_fim_previsto' => 'date',
            'responsavel_id' => 'integer',
            'status_id' => 'integer'
        ];
    }
}
