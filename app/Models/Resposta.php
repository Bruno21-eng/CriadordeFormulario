<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Resposta extends Model
{
    protected $fillable = [
        'createformulario_id',
        'respostas',
        'user_id',
    ];

    protected $casts = [
        'respostas' => 'array',
    ];

    public function formulario(): BelongsTo
    {
        return $this->belongsTo(CreateFormulario::class, 'createformulario_id');
    }
}
