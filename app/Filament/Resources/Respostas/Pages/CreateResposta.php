<?php

namespace App\Filament\Resources\Respostas\Pages;

use App\Filament\Resources\Respostas\RespostaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResposta extends CreateRecord
{
    protected static string $resource = RespostaResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Voltar')
                ->color('gray')
                ->icon('heroicon-m-arrow-left'),
        ];
    }
    public function getTitle(): string
    {
        return "Visualizar Respostas";
    }
    public function getBreadcrumb(): string
    {
        return 'Visualização'; // Ou "Adicionar", "Gerar", etc.
    }
}

