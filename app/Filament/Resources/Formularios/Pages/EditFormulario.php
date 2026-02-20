<?php

namespace App\Filament\Resources\Formularios\Pages;

use App\Filament\Resources\Formularios\FormularioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFormulario extends EditRecord
{
    protected static string $resource = FormularioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
