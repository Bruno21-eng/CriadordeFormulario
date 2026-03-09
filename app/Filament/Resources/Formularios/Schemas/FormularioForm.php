<?php


namespace App\Filament\Resources\Formularios\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\HtmlString;
use App\Models\CreateFormulario;

class FormularioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Configuração Inicial')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('Createformulario_id')
                            ->label('Selecione o Modelo de Formulário')
                            ->options(CreateFormulario::all()->pluck('titulo', 'id'))
                            ->live()
                            ->required(),
                    ]),
                
                Group::make()
                    ->columnSpanFull()
                    ->schema(function ($get, $record): array {
                        $CreateformularioId = $record?->Createformulario_id ?? $get('Createformulario_id');

                        if (!$CreateformularioId) {
                            return [];
                        }
                        $config = CreateFormulario::find($CreateformularioId);
                        $titulo = $config?->titulo ?? 'Formulário sem Título';

                        return array_merge(
                            [
                                Placeholder::make('titulo_exibicao')
                                    ->hiddenLabel()
                                    ->content(new HtmlString("
                                        <h1 style='font-size: 2rem; font-weight: bold; margin-bottom: 1rem;'>
                                            {$titulo}
                                        </h1>
                                        <hr style='margin-bottom: 2rem; border-top: 1px solid #ccc;'>
                            ")),
                            ],
                            self::FormularioDinamico((int) $CreateformularioId)
                        );
                    })
            ]);
    }

    public static function FormularioDinamico(int $CreateformularioId): array
    {
        $config = CreateFormulario::find($CreateformularioId);

        if (!$config || !isset($config->paginas)) {
            return [];
        }

        $components = [
            Hidden::make('Createformulario_id')->default($CreateformularioId)
        ];

        $totalPaginas = count($config->paginas);

        foreach ($config->paginas as $index => $pagina) {
            if ($totalPaginas > 1) {
                $numeroPagina = $index + 1;
                
                $components[] = Placeholder::make('.')
                    ->content(new HtmlString("
                        <div class='flex items-center space-x-2 bg-gray-100 w-fit px-4 py-2 rounded-full border border-gray-200 shadow-sm mb-4'>
                            <span class='flex h-3 w-3 rounded-full bg-yellow-400 animate-pulse'></span>
                            <span class='text-xs font-bold uppercase tracking-widest text-gray-600'>
                                Visualizando: Página {$numeroPagina} de {$totalPaginas}
                            </span>
                        </div>"   
                    ));
            }    


            $seções = $pagina['Seções'] ?? [];
            
            foreach ($seções as $seção) {
                $camposDaSeção = [];
                $elementos = $seção['Elementos'] ?? [];

                foreach ($elementos as $elemento) {
                    $camposDaSeção[] = self::ElementoParaComponente($elemento);
                }

                $components[] = Section::make($seção['titulo-seção'] ?? 'Dados')
                    ->description($seção['descricao'] ?? '')
                    ->schema($camposDaSeção);
            }
        }

        return $components;
    }

    protected static function ElementoParaComponente(array $data)
{
    if (!isset($data['id-elemento']) || empty($data['id-elemento'])) {
        return Placeholder::make('erro_id')
            ->label('Erro de Configuração')
            ->content('Um elemento foi criado sem ID no formulário original.');
    }

    $nomeDoCampo = "respostas.{$data['id-elemento']}";
    $tipo = $data['tipo-do-elemento'] ?? 'Texto';

    // 2. Envolvemos o match e garantimos valores padrão para labels e opções
    $component =  match ($tipo) {
        'Texto'      => TextInput::make($nomeDoCampo),
        'Imagem'    => FileUpload::make($nomeDoCampo),
        'Seleção'    => Select::make($nomeDoCampo)
                            ->options(collect($data['opcoes-selecao'] ?? [])->pluck('opcao-texto', 'opcao-texto')),
        'Radio'      => Radio::make($nomeDoCampo)
                            ->options(collect($data['opcoes-selecao'] ?? [])->pluck('opcao-texto', 'opcao-texto')),
        'Calendário' => DatePicker::make($nomeDoCampo)
                            ->displayFOrmat('d/m/Y'),
        'RichEditor' => RichEditor::make($nomeDoCampo),
        default      => TextInput::make($nomeDoCampo),
    };
    $component->label($data['nome-elemento'] ?? 'Campo sem Título')
        ->helperText($data['descricao-elemento'] ?? null)
        ->required(fn () => (bool) ($data['Obrigatorio'] ?? false));
    if (in_array($tipo, ['Texto', 'Seleção', 'RichEditor']) && !empty($data['placeholder'])) {
        $component->placeholder($data['placeholder']);
    }

    return $component;
}
}