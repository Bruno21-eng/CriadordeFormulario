<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $casts =[
        'paginas' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'criador_nome',
        'titulo',
        'paginas',
    ];

}
