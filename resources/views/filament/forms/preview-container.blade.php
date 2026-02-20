@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- Assets do Trix Editor --}}
{{-- Carregamento forçado dos scripts do RichEditor (Trix) --}}
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

<style>
    /* CSS para garantir que o editor apareça com altura e cores corretas */
    trix-editor { 
        min-height: 150px !important; 
        background-color: white !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
        padding: 0.75rem !important;
    }
    /* Suporte ao modo escuro do Filament */
    .dark trix-editor { 
        background-color: #111827 !important; 
        color: white !important;
        border-color: #374151 !important;
    }
</style>





<div class="flex flex-col gap-y-8 "> {{-- Substituído space-y por gap-y para Tailwind v4 --}}
    @foreach($paginas as $pagina)
        <x-filament::section>
            <x-slot name="heading" class="mb-4">
                <span class="text-xl font-bold">{{ $titulo ?? 'Sem Título' }}</span>
            </x-slot>

            <div class="flex flex-col gap-y-6 "> {{-- Container para as sessões --}}
                @foreach($pagina['Sessões'] ?? [] as $sessao)
                    <x-filament::section>
                        <x-slot name="heading">
                            <span class="text-lg font-semibold">{{ $sessao['titulo_sessão'] ?? 'Sessão sem título' }}</span>
                        </x-slot>

                        {{-- Container da sessão --}}
                        <div class="p-6 border rounded-xl ">
                            
                            @if(!empty($sessao['descricao']))
                                <div class="mb-6 border-l-4 border-primary-500 pl-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $sessao['descricao'] }}
                                    </p>
                                </div>
                            @endif

                            {{-- Grid de Elementos - Removido Style inline, usando classes v4 --}}
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($sessao['Elementos'] ?? [] as $elemento)
                                    @php
                                        $tipo = $elemento['tipo_do_elemento'] ?? '';
                                        $nome_exibicao = $elemento['nome_elemento'] ?? 'Campo sem nome';
                                        $descricao = $elemento['descricao_elemento'] ?? ''; 
                                        $is_required = !empty($elemento['Obrigatorio']);
                                        $placeholder = $elemento['placeholder'] ?? '';
                                    @endphp

                                    {{-- Card do Elemento com espaçamento interno vertical --}}
                                    <div class="p-4 rounded-lg border bg-white dark:bg-gray-800 shadow-sm flex flex-col gap-y-4">
                                        
                                        {{-- Título e Descrição --}}
                                        <div class="flex flex-col gap-y-1">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $nome_exibicao }}
                                                @if($is_required) <span class="text-danger-600">*</span> @endif
                                            </span>
                                            

                                            @if(!empty($descricao))
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $descricao }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Input --}}
                                        <div class="w-full">
                                            @if($tipo === 'Texto')
                                                <x-filament::input.wrapper>
                                                    <x-filament::input type="text" placeholder="{{ $placeholder }}" disabled />
                                                </x-filament::input.wrapper>

                                            @elseif($tipo === 'RichEditor')
                                                <div class="w-full flex flex-col gap-y-2 py-2">
                                                    {{-- Criamos um ID único para cada editor para evitar o bug do slider --}}
                                                    @php $trixId = 'trix_' . uniqid(); @endphp
                                                    <input type="RichEditor" id="{{ $trixId }}" class="hidden" >
                                                    <trix-editor input="{{ $trixId }}" class="w-full" placeholder="{{ $placeholder }}"></trix-editor>
                                                    
                                                </div>
                                            @elseif($tipo === 'Seleção')
                                                <x-filament::input.wrapper>
                                                    <x-filament::input.select>
                                                        <option>{{ $placeholder ?: 'Selecione uma opção...' }}</option>
                                                        @foreach($elemento['opcoes_selecao'] ?? [] as $opcao)
                                                            <option>{{ $opcao['opcao_texto'] }}</option>
                                                        @endforeach
                                                    </x-filament::input.select>
                                                </x-filament::input.wrapper>
                                            @elseif($tipo === 'Radio')
                                                <div class="flex flex-col gap-y-2">
                                                    @foreach($elemento['opcoes_selecao'] ?? [] as $opcao)
                                                        <label class="inline-flex items-center gap-x-2">
                                                            <input type="radio"  class="form-radio text-primary-600" name="{{ $nome_exibicao }}">
                                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $opcao['opcao_texto'] }}</span>
                                                        </label>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{$opcao['descrição']}}</p>
                                                    @endforeach
                                                </div>

                                            @elseif($tipo === 'Calendário')
                                                <x-filament::input.wrapper>
                                                    <div class="flex items-center p-2 text-gray-400">
                                                        <x-filament::icon icon="heroicon-m-calendar" class="w-5 h-5 mr-2" />
                                                        <span class="text-sm">Selecionar data...</span>
                                                    </div>
                                                </x-filament::input.wrapper>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-filament::section>
                @endforeach
            </div>
        </x-filament::section>
    @endforeach
</div>