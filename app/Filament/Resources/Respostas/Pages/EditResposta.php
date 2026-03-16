<?php

namespace App\Filament\Resources\Respostas\Pages;

use App\Filament\Resources\Respostas\RespostaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResposta extends EditRecord
{
    protected static string $resource = RespostaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
