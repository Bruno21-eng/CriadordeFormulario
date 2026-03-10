<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label("Nome")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("email")
                    ->searchable()
                    ->label("Email"),
                TextColumn::make("created_at")
                    ->label("Criado em:")
                    ->sortable()
                    ->dateTime('d/m/Y H:i'),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([

            ]);
    }
}
