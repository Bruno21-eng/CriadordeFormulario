<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;



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
                    ->label("Criado em")
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y H:i'),
                IconColumn::make('is_admin')
                    ->label('É adm')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->role === 'admin')
                    ->trueIcon('heroicon-s-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
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
