<?php

namespace App\Filament\Resources\CreateFormularios\Pages;

use App\Filament\Resources\CreateFormularios\CreateFormularioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreateFormulario extends CreateRecord
{
    protected static string $resource = CreateFormularioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['criador_nome'] = auth()->user()->name;
        return $data;
    }
}