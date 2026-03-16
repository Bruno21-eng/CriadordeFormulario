<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("name")
                    ->label("Nome")
                    ->required()
                    ->maxLength(100),
                TextInput::make("email")
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->email(),
                TextInput::make("password")
                    ->password()
                    ->required(),
                Toggle::make("role")
                    ->label("É Admin?")
                    ->onColor('success')
                    ->inline(false)
                    ->offColor('danger')
                    ->formatStateUsing(fn($state) => $state === 'admin') // Converte 'admin' para true (ligado)
                    ->dehydrateStateUsing(fn($state) => $state ? 'admin' : 'user') // Salva como 'admin' ou 'user'
                    ->visible(fn() => auth()->user()?->isAdmin()), // Só um admin vê esse botão
            ]);
    }
}
