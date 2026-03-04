<?php

namespace App\Filament\Resources\Respostas;

use App\Models\Resposta;
use App\Filament\Resources\Respostas\Pages;
use App\Filament\Resources\Respostas\Schemas\RespostaForm;
use App\Filament\Resources\Respostas\Tables\RespostasTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use BackedEnum;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;

class RespostaResource extends Resource
{
    protected static ?string $model = Resposta::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    // Se o erro no Form persistir, verifique se o RespostaForm::configure
    // está aceitando (Form $form) como argumento.
    
    public static function form(Schema $schema): Schema
    {
        return RespostaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RespostasTable::configure($table);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRespostas::route('/'),
            'create' => Pages\CreateResposta::route('/create'),
            'edit' => Pages\EditResposta::route('/{record}/edit'),
        ];
    }
}