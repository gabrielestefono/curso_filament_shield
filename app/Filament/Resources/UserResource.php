<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Classes;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = "Aministration";
    
    public static function shouldRegisterNavigation(): bool
    {
        return User::find(Auth::id())->can('showNavigation_user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email(),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(),
                TextInput::make('username')
                    ->label('Usuário')
                    ->required(),
                Select::make('role')
                    ->label('Cargo')
                    ->relationship('role', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        if(!User::find(Auth::id())->can('list_user')){
            abort(403);
        }
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Nome de Usuário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role.name')
                    ->label('Cargo')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->hidden(
                        function(){
                            return !User::find(Auth::id())->can('deleteMany_user');
                        }
                    )
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'deleteMany',
            'create',
            'update',
            'delete',
            'list',
            'showNavigation'
        ];
    }
}
