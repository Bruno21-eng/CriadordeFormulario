<?php

namespace App\Filament\Resources\Respostas\Schemas;

use App\Models\CreateFormulario;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Radio;

class RespostaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações da Resposta')
                    ->columnSpanFull()
                    ->schema([
                        // SÓ MOSTRA O SELECT SE FOR UM NOVO REGISTRO (CREATE)
                        Select::make('formulario_id')
                            ->label('Selecione o Modelo de Formulário')
                            ->options(CreateFormulario::all()->pluck('titulo', 'id'))
                            ->live()
                            ->required()
                            ->hidden(fn($record) => $record !== null) // Esconde se já existir um registro
                            ->afterStateUpdated(fn($set) => $set('respostas', [])),

                        // SÓ MOSTRA O CONTEÚDO SE O REGISTRO JÁ EXISTIR (VIEW/EDIT)
                        Placeholder::make('respostas_view')
                            ->label('Conteúdo Preenchido')
                            ->visible(fn($record) => $record !== null) // Só aparece se tiver registro
                            ->content(function($record) {
                                if (!$record) return ''; // Segurança extra
                                
                                return view('filament.components.respostas-list', [
                                    'respostas' => $record->respostas
                                ]);
                            }),
                    ]),

                // ESTA SEÇÃO SÓ APARECE NA CRIAÇÃO, APÓS SELECIONAR UM FORMULÁRIO
                Section::make('Campos para Preencher')
                    ->visible(fn($get, $record) => $record === null && $get('formulario_id') !== null)
                    ->schema(function ($get) {
                        $formularioId = $get('formulario_id');
                        if (!$formularioId) return [];

                        $formulario = CreateFormulario::find($formularioId);
                        if (!$formulario || !isset($formulario->paginas)) return [];

                        $camposDinamicos = [];

                        foreach ($formulario->paginas as $pagina) {
                            foreach ($pagina['Sessões'] as $sessao) {
                                foreach ($sessao['Elementos'] as $elemento) {
                                    $camposDinamicos[] = self::gerarCampo($elemento);
                                }
                            }
                        }

                        return $camposDinamicos;
                    }),
            ]);
    }

    protected static function gerarCampo(array $elemento)
    {
        $nomeParaSalvar = "respostas." . ($elemento['id-elemento'] ?? 'campo');
        $label = $elemento['nome-elemento'] ?? 'Sem título';

        return match ($elemento['tipo-do-elemento']) {
            'Texto' => TextInput::make($nomeParaSalvar)
                ->label($label)
                ->placeholder($elemento['placeholder'] ?? '')
                ->required($elemento['Obrigatorio'] ?? false),

            'Calendário' => DatePicker::make($nomeParaSalvar)
                ->label($label)
                ->required($elemento['Obrigatorio'] ?? false),

            'RichEditor' => RichEditor::make($nomeParaSalvar)
                ->label($label)
                ->columnSpanFull(),

            'Radio' => Radio::make($nomeParaSalvar)
                ->label($label)
                ->options(collect($elemento['opcoes-selecao'] ?? [])->pluck('opcao-texto', 'opcao-texto')->toArray())
                ->required($elemento['Obrigatorio'] ?? false),

            'Seleção' => Select::make($nomeParaSalvar)
                ->label($label)
                ->options(collect($elemento['opcoes-selecao'] ?? [])->pluck('opcao-texto', 'opcao-texto')->toArray())
                ->required($elemento['Obrigatorio'] ?? false),

            default => Placeholder::make($nomeParaSalvar)
                ->label($label)
                ->content('Tipo de campo não suportado no Admin'),
        };
    }
}