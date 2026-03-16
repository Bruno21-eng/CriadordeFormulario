<?php

namespace App\Models;

use App\Models\Formulario;
use App\Models\Resposta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreateFormulario extends Model
{
    protected $table = 'Createformularios';

    protected $casts = [
        'paginas' => 'array',
        'emails' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'criador_nome',
        'titulo',
        'senha',
        'emails',
        'paginas',
    ];
    
    public function respostas(): HasMany
    {
        // Relaciona com o model que guarda os preenchimentos (Formulario)
        // usando a chave estrangeira que você definiu na migration
        return $this->hasMany(Resposta::class, 'createformulario_id');
    }
}
