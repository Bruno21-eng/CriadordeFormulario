<?php

namespace App\Filament\Resources\CreateFormularios;

use App\Filament\Resources\CreateFormularios\Pages\CreateCreateFormulario;
use App\Filament\Resources\CreateFormularios\Pages\EditCreateFormulario;
use App\Filament\Resources\CreateFormularios\Pages\ListCreateFormularios;
use App\Filament\Resources\CreateFormularios\Schemas\CreateFormularioForm;
use App\Filament\Resources\CreateFormularios\Tables\CreateFormulariosTable;
use App\Models\CreateFormulario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CreateFormularioResource extends Resource
{
    protected static ?string $model = CreateFormulario::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'CreateFormulario';

    public static function form(Schema $schema): Schema
    {
        return CreateFormularioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreateFormulariosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCreateFormularios::route('/'),
            'create' => CreateCreateFormulario::route('/create'),
            'edit' => EditCreateFormulario::route('/{record}/edit'),
        ];
    }
}
