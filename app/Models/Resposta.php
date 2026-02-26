<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Resposta extends Model
{
    protected $fillable = [
        'Createformulario_id',
        'respostas',
        'user_id',
    ];

    protected $casts = [
        'respostas' => 'array',
    ];

    public function formulario() : BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }
}
