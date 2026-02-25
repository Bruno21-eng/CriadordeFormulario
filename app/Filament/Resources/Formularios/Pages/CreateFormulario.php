<?php

namespace App\Filament\Resources\Formularios\Pages;

use App\Filament\Resources\Formularios\FormularioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormulario extends CreateRecord
{
    protected static string $resource = FormularioResource::class;

    public function getTitle(): string
    {
        return "Visualizar Fórmularios";
    }
    public function getBreadcrumb(): string
    {
        return 'Visualização'; // Ou "Adicionar", "Gerar", etc.
    }
}
