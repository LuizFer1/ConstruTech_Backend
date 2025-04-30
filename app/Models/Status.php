<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use SoftDeletes;
    public $table  = 'status';

    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function obras(): HasMany
    {
        return $this->hasMany(Obra::class);
    }
}
