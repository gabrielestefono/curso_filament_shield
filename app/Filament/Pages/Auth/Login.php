<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as FilamentLogin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;

class Login extends FilamentLogin
{
	protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

	protected function getEmailFormComponent(): Component
    {
        return TextInput
		::make('username')
            ->label(__('Username'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
}