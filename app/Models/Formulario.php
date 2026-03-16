<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formulario extends Model
{
    use HasFactory;

    // Permite que o Laravel salve esses campos em massa
    protected $fillable = [
        'titulo',
        'respostas',
    ];

    // Transforma o JSON do banco de dados em Array do PHP automaticamente
    protected $casts = [
        'respostas' => 'array',
    ];

    public function Createformulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class, 'Createformulario_id');
    }
}