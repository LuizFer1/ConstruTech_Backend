<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'telefone',
        'endereco',
        'municipio',
        'data_nascimento'
    ];

    public function obras(): HasMany{
        return $this->hasMany(Obra::class);
    }
}
