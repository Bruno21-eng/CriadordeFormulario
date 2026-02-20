<?php

namespace App\Filament\Resources\Formularios\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;

class FormulariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('titulo')
                    ->label('Título do Formulário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('criador_nome')
                    ->label('Criador')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ViewAction::make(),
                ]),
            ])
            ->toolbarActions([
            ]);
    }
}
