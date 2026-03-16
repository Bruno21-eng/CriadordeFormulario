<?php

namespace App\Filament\Resources\Formularios;

use App\Filament\Resources\Formularios\Pages\CreateFormulario as CreatePage;
use App\Filament\Resources\Formularios\Pages\EditFormulario;
use App\Filament\Resources\Formularios\Pages\ListFormularios;
use App\Filament\Resources\Formularios\Schemas\FormularioForm;
use App\Filament\Resources\Formularios\Tables\FormulariosTable;
use App\Models\Formulario;
use App\Models\CreateFormulario as CreateModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormularioResource extends Resource
{
    protected static ?string $model = CreateModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboard;

    protected static ?string $modelLabel = ' Formulários';

    protected static ?string $recordTitleAttribute = 'FormularioResource';

    public static function form(Schema $schema): Schema
    {
        return FormularioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormulariosTable::configure($table);
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
            'index' => ListFormularios::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditFormulario::route('/{record}/edit'),
        ];
    }
}