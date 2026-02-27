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
        // Salva as respostas no mesmo formato que o seu RespostaResource faria
        Resposta::create([
            'Createformulario_id' => $id,
            'respostas' => $request->input('respostas'),
        ]);

        return back()->with('sucesso', 'Formulário enviado com sucesso!');
    }
}