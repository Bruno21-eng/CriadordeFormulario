<?php

namespace App\Filament\Resources\CreateFormularios\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action as FormAction;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Width;

class CreateFormularioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Actions::make([
                    FormAction::make('preview')
                        ->label('Pré-visualizar')
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->modalHeading('Visualização do Formulário')
                        ->modalSubmitAction(false)
                        ->modalWidth('7xl')
                        
                        ->modalContent(fn ($get) => view('filament.forms.preview-container', [
                            'paginas' => $get('paginas'),
                            'titulo' => $get('titulo'),
                        ])),
                ]),
                TextInput::make('titulo')
                    ->required()
                    ->label('Título do Formulário')
                    ->columnSpanFull(),
                TextInput::make('senha')
                    ->label('Senha de Acesso (opcional)')
                    ->helperText('Defina uma senha para proteger o acesso ao formulário. Deixe em branco para acesso livre.')
                    ->password()
                    ->columnSpanFull(),
                Repeater::make('paginas')
                ->label('Páginas')
                ->schema([
                    Repeater::make('Sessões')
                        ->inset()
                        ->schema([
                            TextInput::make('titulo-sessão')
                                ->required()
                                ->label('Título da Sessão')
                                ->live()
                                ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create'
                                 ? $set('id-sessao', Str::slug($state)) : null),
                            TextInput::make('id-sessao')->required()->label('Id da Sessão')->disabled()->dehydrated(),
                            TextInput::make('descricao')->label('Descrição da Sessão'),
                            Repeater::make('Elementos')
                                ->reorderableWithButtons()
                                ->collapsible()
                                ->itemLabel(fn (array $state): ?string => $state['tipo-do-elemento'] ?? 'Novo Elemento')
                                ->schema([
                                    Select::make('tipo-do-elemento')
                                        ->options([
                                            'Texto' => 'Campo de texto',
                                            'Radio' => 'Botão de opção',
                                            'RichEditor' => 'Campo de texto avançado',
                                            'Imagem' => 'Carregar Imagem',
                                            'Seleção' => 'Caixa de Seleção',
                                            'Calendário' => 'Campo de Data',
                                        ])
                                        ->live()
                                        ->required(),
                                    Toggle::make('Obrigatorio')
                                        ->label('É Obrigatório?')
                                        ->live()
                                        ->inline(false)
                                        ->onColor('success'),
                                    Section::make()
                                        ->label('Configurações Básicas')
                                        ->description('Preencha os detalhes do campo selecionado')
                                        ->compact()
                                        ->visible(fn ($get) => in_array($get('tipo-do-elemento'), ['Texto','Radio','RichEditor','Imagem','Seleção', 'Calendário']))
                                        ->columnSpan(fn($get) => in_array($get('tipo-do-elemento'), ['Texto', 'Imagem', 'RichEditor', 'Calendário']) ? 'full' : 1)
                                        ->schema([
                                            TextInput::make('nome-elemento')
                                                ->label('Nome do Elemento')
                                                ->required()
                                                ->placeholder('Ex: Nome Completo')
                                                ->live()
                                                ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create'
                                                ? $set('id-elemento', Str::slug($state)) : null),
                                            TextInput::make('id-elemento')
                                                ->label('ID do Elemento')
                                                ->required()
                                                ->disabled()
                                                ->dehydrated()
                                                ->placeholder('Ex: nome-usuario'),
                                            TextInput::make('descricao-elemento')
                                                ->label('Descrição do Elemento')
                                                ->live()
                                                ->placeholder('Digite uma descrição ou instrução para o usuário (ñ obrigatório)'),
                                            TextInput::make('placeholder')
                                                ->label('Placeholder (Dica)')
                                                ->placeholder('Digite aqui...')
                                                ->hidden(fn ($get) => in_array($get('tipo-do-elemento'), ['Imagem', 'Seleção', 'Radio', 'Calendário'])),
                                        ]),
                                    Repeater::make('opcoes-selecao')
                                        ->label('Opções da Lista')
                                        ->schema([
                                            TextInput::make('opcao-texto')->label('Texto da Opção')->required(),
                                            TextInput::make('descrição')->label('Descrição da Opção')->hidden(fn ($get) => $get('../../tipo-do-elemento') === 'Seleção'),
                                        ])
                                        ->visible(fn ($get) => in_array($get('tipo-do-elemento'), ['Seleção', 'Radio']))
                                        ->addActionLabel('Adicionar Opção'),
                                    
                                ])
                                ->addActionLabel('Adicionar Elemento')
                                ->addAction(
                                    fn (Action $action) => $action->icon('heroicon-s-plus'),
                                )
                                ->columns(2),
                        ])
                        ->addActionLabel('Adicionar Sessão')
                        ->addAction(
                            fn (Action $action) => $action
                            ->icon('heroicon-m-plus-circle'),
                        )
                ])
                ->addActionLabel('Adicionar Página')
                ->addAction(
                    fn (Action $action) => $action->icon('heroicon-s-document-plus'),
                )
                ->columnSpanFull()
            ]);                             
        }

}