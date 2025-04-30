<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
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
            'nome' => 'min:20',
            'data_nascimento' => 'date',
            'cpf_cnpj' => 'cpf_ou_cnpj',
            'telefone' => 'celular_com_ddd',
            'endereco' => 'min:20'
        ];
    }
}
