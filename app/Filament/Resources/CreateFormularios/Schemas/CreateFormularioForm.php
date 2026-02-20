<?php


namespace App\Filament\Resources\CreateFormularios\Schemas;

use Filament\Schemas\Schema;
// Componentes de LAYOUT (Schemas)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
// Componentes de INPUT (Forms)
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use App\Models\Formulario;

class CreateFormularioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Configuração Inicial')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('formulario_id')
                            ->label('Selecione o Modelo de Formulário')
                            ->options(Formulario::all()->pluck('titulo', 'id'))
                            ->live()
                            ->required(),
                    ]),

                // No v5, Group também costuma vir de Schemas\Components
                Group::make()
                    ->schema(function ($get, $record): array {
                        $formularioId = $record?->formulario_id ?? $get('formulario_id');

                        if (!$formularioId) {
                            return [];
                        }

                        return self::getDynamicForm((int) $formularioId);
                    })
            ]);
    }

    public static function getDynamicForm(int $formularioId): array
    {
        $config = Formulario::find($formularioId);

        if (!$config || !isset($config->paginas)) {
            return [];
        }

        $components = [
            Hidden::make('formulario_id')->default($formularioId)
        ];

        foreach ($config->paginas as $pagina) {
            $sessoes = $pagina['Sessões'] ?? [];
            
            foreach ($sessoes as $sessao) {
                $camposDaSessao = [];
                $elementos = $sessao['Elementos'] ?? [];

                foreach ($elementos as $elemento) {
                    $camposDaSessao[] = self::mapElementToFilamentComponent($elemento);
                }

                // Usando a Section do namespace de Schemas
                $components[] = Section::make($sessao['titulo_sessão'] ?? 'Dados')
                    ->description($sessao['descricao'] ?? '')
                    ->schema($camposDaSessao);
            }
        }

        return $components;
    }

    protected static function mapElementToFilamentComponent(array $data)
{
    // 1. Verificação de segurança: Se não houver ID, não podemos criar o campo
    if (!isset($data['id_elemento']) || empty($data['id_elemento'])) {
        // Retornamos um Placeholder ou um campo oculto para não quebrar o loop
        return \Filament\Forms\Components\Placeholder::make('erro_id')
            ->label('Erro de Configuração')
            ->content('Um elemento foi criado sem ID no formulário original.');
    }

    $nomeDoCampo = "respostas.{$data['id_elemento']}";

    // 2. Envolvemos o match e garantimos valores padrão para labels e opções
    return (match ($data['tipo_do_elemento'] ?? 'Texto') {
        'Texto'      => TextInput::make($nomeDoCampo),
        'Seleção'    => Select::make($nomeDoCampo)
                            ->options(collect($data['opcoes_selecao'] ?? [])->pluck('opcao_texto', 'opcao_texto')),
        'Radio'      => Radio::make($nomeDoCampo)
                            ->options(collect($data['opcoes_selecao'] ?? [])->pluck('opcao_texto', 'opcao_texto')),
        'Calendário' => DatePicker::make($nomeDoCampo),
        'RichEditor' => \Filament\Forms\Components\RichEditor::make($nomeDoCampo),
        default      => TextInput::make($nomeDoCampo),
    })
    ->label($data['nome_elemento'] ?? 'Campo sem Título')
    ->required(fn () => (bool) ($data['Obrigatorio'] ?? false));
}
}