<?php

namespace App\Filament\Resources\Respostas;

use App\Filament\Resources\Respostas\Pages\CreateResposta;
use App\Filament\Resources\Respostas\Pages\EditResposta;
use App\Filament\Resources\Respostas\Pages\ListRespostas;
use App\Filament\Resources\Respostas\Schemas\RespostaForm;
use App\Filament\Resources\Respostas\Tables\RespostasTable;
use App\Models\Resposta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RespostaResource extends Resource
{
    protected static ?string $model = Resposta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'RespostaResource';

    public static function form(Schema $schema): Schema
    {
        return RespostaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RespostasTable::configure($table);
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
            'index' => ListRespostas::route('/'),
            'create' => CreateResposta::route('/create'),
            'edit' => EditResposta::route('/{record}/edit'),
        ];
    }
}
