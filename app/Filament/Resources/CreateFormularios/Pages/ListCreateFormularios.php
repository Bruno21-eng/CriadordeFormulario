<?php

namespace App\Filament\Resources\CreateFormularios\Pages;

use App\Filament\Resources\CreateFormularios\CreateFormularioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreateFormularios extends ListRecords
{
    protected static string $resource = CreateFormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Criação de Formulários'),
        ];
    }
}
