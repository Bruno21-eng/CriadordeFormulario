<?php

namespace App\Filament\Resources\CreateFormularios\Pages;

use App\Filament\Resources\CreateFormularios\CreateFormularioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCreateFormulario extends EditRecord
{
    protected static string $resource = CreateFormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
