@vite(['resources/css/app.css', 'resources/js/app.js'])

@php
    // Se isPreview existir e for verdadeiro, $visu é true, senão é false
    $visu = isset($isPreview) && $isPreview;
    $action = $visu ? '#' : route('formulario.responder', $formulario->id);
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formulario->titulo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen py-12 px-4">
    

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-t-lg shadow-sm p-8 border-t-8 border-yellow-400">
            <h1 class="text-4xl font-bold text-gray-800">{{ $formulario->titulo }}</h1>
            @if(session('sucesso'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('sucesso') }}
                </div>
            @endif
        </div>

        @php
            $action = isset($isPreview) ? '#' : route('formulario.responder', $formulario->id);
        @endphp
        <form x-data="{ step: 0, totalSteps: {{ count($formulario->paginas) - 1 }} }" @keydown.enter.prevent action="{{ $action }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @if(isset($isPreview))
                <div class="p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 mb-10">
                    <strong>Modo de Visualização:</strong> Os botões e envios estão desabilitados.
                </div>
                
            @endif
                @foreach($formulario->paginas as $paginaIndex => $pagina)
                    <div x-show="step === {{ $paginaIndex }}" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-4"
                        x-transition:enter-end="opacity-100 transform translate-x-0">

                        @if(count($formulario->paginas) > 1)
                            <div class="mb-10">
                                {{-- 1. INDICADOR DISCRETO (Aparece apenas no Preview/Admin) --}}
                                @if(isset($isPreview) && $isPreview)
                                    <div class="flex items-center space-x-2 bg-gray-100 w-fit px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                                        <span class="flex h-3 w-3 rounded-full bg-yellow-400 animate-pulse"></span>
                                        <span class="text-xs font-bold uppercase tracking-widest text-gray-600">
                                            Visualizando: Página {{ $loop->iteration }} de {{ count($formulario->paginas) }}
                                        </span>
                                    </div>
                                @else
                                    {{-- 2. LOCALIZADOR NORMAL (Aparece apenas para o Usuário Final) --}}
                                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                                        <div class="flex items-center justify-between relative max-w-xl mx-auto">
                                            @foreach($formulario->paginas as $sIndex => $p)
                                                <div class="flex flex-col items-center relative z-10">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors border-2"
                                                        :class="step === {{ $sIndex }} ? 'bg-yellow-500 text-white border-yellow-500' : (step > {{ $sIndex }} ? 'bg-green-500 text-white border-green-500' : 'bg-white text-gray-400 border-gray-200')">
                                                        
                                                        <span x-show="step > {{ $sIndex }}">✓</span>
                                                        <span x-show="step <= {{ $sIndex }}">{{ $loop->iteration }}</span>
                                                    </div>
                                                    
                                                    <span class="text-[10px] mt-2 font-bold uppercase" 
                                                        :class="step === {{ $sIndex }} ? 'text-yellow-600' : 'text-gray-400'">
                                                        Pág. {{ $loop->iteration }}
                                                    </span>
                                                </div>
                                            @endforeach
                                            <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 -z-0"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif


                        @foreach($pagina['Seções'] as $sessao)
                            <div class="bg-white p-8 shadow-sm rounded-lg border border-gray-100 relative overflow-hidden mb-6">
                                <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>
                                <h2 class="text-2xl font-semibold text-gray-700 border-b pb-2 mb-6">{{ $sessao['titulo-seção'] }}</h2>
                                
                                <div class="space-y-6">
                                    @foreach($sessao['Elementos'] as $elemento)
                                        @php
                                            $idElemento = $elemento['id-elemento'] ?? 'temp-' . $loop->index;
                                            $fieldName = "respostas[{$idElemento}]";
                                        @endphp
                                        
                                        <div>
                                            <label class="block text-gray-700 font-bold mb-2">
                                                {{ $elemento['nome-elemento'] }}
                                                @if($elemento['Obrigatorio']) <span class="text-red-500">*</span> @endif
                                            </label>

                                        @php $fieldName = "respostas[{$elemento['id-elemento']}]"; @endphp

                                        @switch($elemento['tipo-do-elemento'])
                                            @case('Texto')
                                                <input type="text" name="{{ $fieldName }}" @disabled($visu) placeholder="{{ $elemento['placeholder'] }}"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 border p-3"
                                                    {{ $elemento['Obrigatorio'] ? 'required' : '' }}>
                                                    
                                                @break

                                            @case('Calendário')
                                                <input type="date" name="{{ $fieldName }}"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 border p-3"
                                                    {{ $elemento['Obrigatorio'] ? 'required' : '' }}>
                                                @break

                                            @case('Seleção')
                                                <select name="{{ $fieldName }}" class="w-full border-gray-300 rounded-lg shadow-sm border p-3" {{ $elemento['Obrigatorio'] ? 'required' : '' }}>
                                                    <option value="">Selecione uma opção</option>
                                                    @foreach($elemento['opcoes-selecao'] as $opcao)
                                                        <option value="{{ $opcao['opcao-texto'] }}">{{ $opcao['opcao-texto'] }}</option>
                                                    @endforeach
                                                </select>
                                                @break

                                            @case('Radio')
                                                <div class="space-y-2">
                                                    @foreach($elemento['opcoes-selecao'] as $opcao)
                                                        <label class="flex items-center space-x-3">
                                                            <input type="radio" name="{{ $fieldName }}" value="{{ $opcao['opcao-texto'] }}" class="text-yellow-500 focus:ring-yellow-500">
                                                            <span>{{ $opcao['opcao-texto'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @break

                                            @case('RichEditor')
                                                <textarea name="{{ $fieldName }}" rows="4" @disabled($visu)
                                                    class="w-full border-gray-300 rounded-lg shadow-sm border p-3"
                                                    placeholder="{{ $elemento['placeholder'] }}"></textarea>
                                                @break
                                            
                                            @case('Imagem')
                                                <div class="mt-2">
                                                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200">
                                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500 font-semibold">Clique para fazer upload</p>
                                                        </div>
                                                        
                                                        {{-- Agora o ID vai funcionar corretamente --}}
                                                        <input type="file" 
                                                            name="{{ $fieldName }}" 
                                                            @disabled($visu)
                                                            id="file_{{ $idElemento }}"
                                                            onchange="updateFileName(this, 'label_{{ $idElemento }}')"
                                                            accept="image/*" 
                                                            class="hidden" 
                                                            {{ $elemento['Obrigatorio'] ? 'required' : '' }} />
                                                    </label>
                                                    {{-- Esse parágrafo mostrará o nome do arquivo selecionado --}}
                                                    <p id="label_{{ $idElemento }}" class="mt-2 text-sm text-blue-600 font-medium"></p>                                            
                                            @break
                                        @endswitch
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center mt-8 pb-10">
                        <button type="button" 
                                x-show="step > 0" 
                                @click="step--; window.scrollTo({top: 0, behavior: 'smooth'})" 
                                class="bg-white border border-gray-300 text-gray-700 font-bold py-3 px-8 rounded-lg hover:bg-gray-50 transition">
                            Anterior
                        </button>
                        
                        <div x-show="step === 0"></div> <button type="button" 
                                x-show="step < totalSteps" 
                                @click="step++; window.scrollTo({top: 0, behavior: 'smooth'})" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                            Próximo
                        </button>

                        @if(!isset($isPreview))
                            <button type="submit" 
                                    x-show="step === totalSteps" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-10 rounded-lg shadow-md transition">
                                Enviar Respostas
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
<script>
    function updateFileName(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files.length > 0) {
            label.innerText = "✅ Arquivo selecionado: " + input.files[0].name;
        } else {
            label.innerText = "";
        }
    }
</script>
</body>
</html>