<?php

use App\Http\Controllers\FormularioPublicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Rota para exibir o formulário
Route::get('/f/{id}', [FormularioPublicoController::class, 'show'])->name('formulario.publico');

// Rota para receber as respostas (POST)
Route::post('/f/{id}/responder', [FormularioPublicoController::class, 'store'])->name('formulario.responder');