<?php

namespace App\Filament\Resources\CreateFormularios\Pages;

use App\Filament\Resources\CreateFormularios\CreateFormularioResource;
use Filament\Resources\Pages\CreateRecord;
use Mail;
use App\Mail\EnviarFormMail;


class CreateCreateFormulario extends CreateRecord
{
    protected static string $resource = CreateFormularioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['criador_nome'] = auth()->user()->name;
        return $data;
    }
    protected function afterCreate(): void
    {
        $formulario = $this->record;
        $emails = $formulario->emails;
        if ($emails) {
            $urlFormulario = route('formulario.publico', $formulario);

            foreach ($emails as $item) {
                $enderecoDestino = $item['email'];

                Mail::to($enderecoDestino)->send(new EnviarFormMail($formulario->titulo, $urlFormulario));
            }
        }
    }
}