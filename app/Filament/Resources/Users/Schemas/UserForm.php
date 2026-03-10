<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
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
            ]);
    }
}
