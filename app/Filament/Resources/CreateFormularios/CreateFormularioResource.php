<?php

namespace App\Filament\Resources\CreateFormularios;

use App\Filament\Resources\CreateFormularios\Pages\CreateCreateFormulario;
use App\Filament\Resources\CreateFormularios\Pages\EditCreateFormulario;
use App\Filament\Resources\CreateFormularios\Pages\ListCreateFormularios;
use App\Filament\Resources\CreateFormularios\Schemas\CreateFormularioForm;
use App\Filament\Resources\CreateFormularios\Tables\CreateFormulariosTable;
use App\Models\CreateFormulario;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


use App\Mail\EnviarFormMail;
use Illuminate\Support\Facades\Mail;

class CreateFormularioResource extends Resource
{
    protected static ?string $model = CreateFormulario::class;

    protected static ?string $modelLabel = 'Criação de Formulários';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocument;

    protected static ?string $recordTitleAttribute = 'CreateFormularioResource';

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
            'index' => Pages\ListCreateFormularios::route('/'),
            'create' => Pages\CreateCreateFormulario::route('/create'),
            'edit' => Pages\EditCreateFormulario::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Se o usuário logado NÃO for admin, filtramos pelo ID dele
        if (!auth()->user()?->isAdmin()) {
            // Supondo que na sua tabela 'create_formularios' a coluna se chama 'user_id'
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

}