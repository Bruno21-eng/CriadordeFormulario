<?php

namespace App\Filament\Resources\Formularios\Pages;

use App\Filament\Resources\Formularios\FormularioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormulario extends CreateRecord
{
    protected static string $resource = FormularioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['criador_nome'] = auth()->user()->name;
        return $data;
    }
}
