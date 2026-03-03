<?php

namespace App\Filament\Resources\Respostas\Tables;

use Filament\Actions\Action;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
// Importação direta e absoluta
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class RespostasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")
                ->label('resposta n°'),
                TextColumn::make('user.name')
                    ->label('Nome do Usuário')
                    ->placeholder('Anônimo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('formulario.titulo')
                    ->label('Formulário')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data da Resposta')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('formulario.criador_nome')
                ->label('Criador do Formulário')
                ->alignCenter(),
            ])
            ->filters([
                //
            ])
            
            ->recordActions([
                // Agora usamos a classe importada diretamente
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}