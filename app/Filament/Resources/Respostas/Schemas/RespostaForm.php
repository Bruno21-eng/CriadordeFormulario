<?php

namespace App\Filament\Resources\Respostas\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RespostaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conteúdo preenchido')
                    ->columnSpanFull()
                    ->schema([
                        // Mudamos o import lá no topo para Filament\Forms\Components\KeyValueField
                        Placeholder::make('respostas_view')
                            ->label('Respostas:')
                            ->content(fn ($record) => view('filament.components.respostas-list', ['respostas' => $record->respostas])),
                    ]),
            ]);
    }
}