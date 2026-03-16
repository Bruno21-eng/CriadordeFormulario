<?php

namespace App\Http\Controllers;

use App\Models\CreateFormulario;
use App\Models\Resposta;
use Illuminate\Http\Request;

class FormularioPublicoController extends Controller
{
    public function show($id)
    {
        $formulario = CreateFormulario::findOrFail($id);
        return view('formularios.publico', compact('formulario'));
    }

    public function store(Request $request, $id)
    {
        $respostas = $request->input('respostas', []);

        // 1. Verificar se existem arquivos sendo enviados
        if ($request->hasFile('respostas')) {
            foreach ($request->file('respostas') as $elementoId => $arquivo) {
                // 2. Salva o arquivo na pasta 'respostas' dentro de 'storage/app/public'
                $caminho = $arquivo->store('respostas', 'public');

                // 3. Substitui o valor no array pelo caminho do arquivo salvo
                $respostas[$elementoId] = $caminho;
            }
        }

        // 4. Salva no banco de dados
        Resposta::create([
            'createformulario_id' => $id,
            'respostas' => $respostas,
            'user_id' => auth()->check() ? auth()->user()->id : null,
        ]);

        return back()->with('sucesso', 'Formulário enviado com sucesso!');
    }
}