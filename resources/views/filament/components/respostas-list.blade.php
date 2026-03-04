@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="space-y-2">
    @if(is_array($respostas) || is_object($respostas))
        <div class="grid grid-cols-1 border rounded-lg overflow-hidden shadow-sm">
            @foreach($respostas as $pergunta => $resposta)
                <div class="flex border-b last:border-0">
                    <div class="w-1/3 bg-gray-50 p-3 border-r font-bold text-gray-700 text-sm">
                        {{ $pergunta }}
                    </div>
                    <div class="w-2/3 p-3 bg-white text-gray-600 text-sm italic">
                        {{ is_array($resposta) ? implode(', ', $resposta) : $resposta }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic">Nenhuma resposta registrada ou formato inválido.</p>
    @endif
</div>