<?php

namespace App\Models;

use App\Mail\LinkFormularioMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Formulario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreateFormulario extends Model
{
    protected $table = 'Createformularios';
    
    protected $casts =[
        'paginas' => 'array',
        'emails'=> 'array',
    ];
    protected $fillable = [
        'user_id',
        'criador_nome',
        'titulo',
        'senha',
        'emails',
        'paginas',
    ];
    protected static function booted()
    {
        static::created(function ($createFormulario) {
            // Enviar e-mails para os destinatários
            foreach ($createFormulario->emails as $email) {
                Mail::to($email['email'])->send(new LinkFormularioMail(
                    titulo: $createFormulario->titulo,
                    url: route('formulario.show', ['id' => $createFormulario->id])
                ));
            }
        });
    }
    public function respostas(): HasMany
{
    // Relaciona com o model que guarda os preenchimentos (Formulario)
    // usando a chave estrangeira que você definiu na migration
    return $this->hasMany(Formulario::class, 'Createformulario_id');
}
}
