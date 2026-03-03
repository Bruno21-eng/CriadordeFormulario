<?php

namespace App\Filament\Resources\Formularios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
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
                        return is_array($record->paginas) ? count($record->paginas) : 1;
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
                    ->label('Protegido')
                    ->getStateUsing(fn($record) => !empty($record->senha))
                    ->boolean()
                    ->trueIcon('heroicon-m-lock-closed')
                    ->falseIcon('heroicon-m-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->alignCenter(),
            ])
            ->recordUrl(function ($record) {
            })
            ->filters([
                //
            ])
            ->actions([
                Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-m-pencil')
                    ->color('primary')
                    
                    // A mágica acontece aqui:
                    ->mountUsing(function (Action $action, \App\Models\CreateFormulario $record) {
                        if (empty($record->senha)) {
                            return redirect()->to(route('formulario.publico', $record));
                        }
                    })
                    // Se tiver senha, ele pede os dados abaixo:
                    ->form([
                        TextInput::make('senha_digitada')
                            ->label('Este formulário precisa de Senha')
                            ->password()
                            ->placeholder('Digite a senha para acessar')
                            ->required(),
                    ])
                    ->action(function (\App\Models\CreateFormulario $record, array $data) {
                        // Verifica se a senha está correta
                        if ($data['senha_digitada'] === $record->senha) {
                            return redirect()->to(route('formulario.publico', $record));
                        }
                        // Se errar, manda uma notificação
                        Notification::make()
                            ->title('Senha Incorreta')
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
