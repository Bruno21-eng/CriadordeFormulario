<?php

namespace App\Filament\Resources\Formularios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;

class FormulariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título do Formulário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('criador_nome')
                    ->label('Criado por'),

                TextColumn::make('paginas')
                    ->label('Páginas')
                    ->getStateUsing(function ($record) {
                        return is_array($record->paginas)? count($record->paginas):1;
                    })
                    ->badge()
                    ->color('primary'),
                TextColumn::make('respostas_count')
                    ->label('Total de Respostas')
                    ->counts('respostas') // Isso usa o método que criamos no Model
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y H:i'),
                IconColumn::make('senha')
                    ->label('Acesso')
                    ->getStateUsing(fn ($record) => !empty($record->senha)) 
                    ->boolean()
                    // No Filament, usamos strings para os ícones
                    ->trueIcon('heroicon-m-lock-closed') 
                    ->falseIcon('heroicon-m-lock-open')
                    ->trueColor('danger')  // Vermelho para bloqueado
                    ->falseColor('success') // Verde para aberto
                    ->alignCenter(),
            ])
            ->recordUrl(function($record) {
                return route('formulario.publico' , ['id' => $record->id]);
            })
            ->filters([
                //
            ])
            ->actions([
                Action::make('responder')
                    ->label('Responder Formulário')
                    ->icon('heroicon-m-pencil')
                    ->color('primary')
                    ->url(fn ($record) => "admin/respostas/create?formulario_id={$record->id}"),
            ])
            ->recordActions([

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
