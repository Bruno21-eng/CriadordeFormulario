<?php

namespace App\Filament\Resources\Respostas\Schemas;

use App\Models\CreateFormulario;
use App\Models\Resposta;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;

class RespostaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SEÇÃO 1: SELETOR DE MODELO (Apenas na Criação)
                Section::make('Filtro de Histórico')
                    ->description('Selecione um formulário para ver todas as respostas já enviadas.')
                    ->columnSpanFull()
                    ->hidden(fn($record) => $record !== null) // ESCONDE se estiver visualizando um item já salvo
                    ->schema([
                        Select::make('formulario_id')
                            ->label('Selecione o Modelo de Formulário')
                            ->options(CreateFormulario::all()->pluck('titulo', 'id'))
                            ->live()
                            ->required()
                            ->native(false),
                    ]),

                // SEÇÃO 2: VISUALIZAÇÃO DE ITEM ÚNICO (Apenas ao clicar em "Visualizar" na Tabela)
                Section::make('Conteúdo da Resposta')
                    ->columnSpanFull()
                    ->visible(fn($record) => $record !== null) // SÓ APARECE se já existir um registro (View Mode)
                    ->schema([
                        Placeholder::make('resposta_view')
                            ->label(fn($record) => "Resposta enviada por: " . ($record->user?->name ?? 'Usuário Anônimo'))
                            ->content(function ($record) {
                                return view('filament.components.respostas-list', [
                                    'respostas' => $record->respostas
                                ]);
                            }),
                    ]),

                // SEÇÃO 3: LISTAGEM HISTÓRICA (Apenas na Criação, após selecionar o modelo)
                Section::make('Histórico de Envios')
                    ->description('Veja abaixo tudo o que já foi respondido para este modelo.')
                    ->columnSpanFull()
                    ->visible(fn($get, $record) => $record === null && $get('formulario_id') !== null)
                    ->schema(function ($get) {
                        $formularioId = $get('formulario_id');

                        $envios = Resposta::where('createformulario_id', $formularioId)
                            ->with('user')
                            ->latest()
                            ->get();

                        if ($envios->isEmpty()) {
                            return [
                                Placeholder::make('aviso')
                                    ->label('')
                                    ->content('Nenhuma resposta encontrada para este modelo ainda.')
                            ];
                        }

                        $componentes = [];
                        foreach ($envios as $envio) {
                            $componentes[] = Placeholder::make('envio_' . $envio->id)
                                ->label("Enviado por: " . ($envio->user?->name ?? 'Anônimo') . " - " . $envio->created_at->format('d/m/Y H:i'))
                                ->content(fn() => view('filament.components.respostas-list', [
                                    'respostas' => $envio->respostas
                                ]));
                        }

                        return $componentes;
                    }),
            ]);
    }
}