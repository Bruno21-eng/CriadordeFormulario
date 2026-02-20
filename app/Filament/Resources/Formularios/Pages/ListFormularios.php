<?php

namespace App\Filament\Resources\Formularios\Pages;

use App\Filament\Resources\Formularios\FormularioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormularios extends ListRecords
{
    protected static string $resource = FormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
