<?php

namespace App\Filament\Resources\Respostas\Pages;

use App\Filament\Resources\Respostas\RespostaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRespostas extends ListRecords
{
    protected static string $resource = RespostaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Visualizar Respostas'),
        ];
    }
}
