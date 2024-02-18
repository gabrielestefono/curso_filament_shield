<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Classes;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\ClassesResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Facades\Auth;

class ClassesResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = "Academy Management";

    public static function shouldRegisterNavigation(): bool
    {
        return User::find(Auth::id())->can('showNavigation_classes');
    }

    public static function form(Form $form): Form
    {
        if(!User::find(Auth::id())->can('create_classes')){
            abort(403);
        }
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        if(!User::find(Auth::id())->can('list_classes')){
            abort(403);
        }
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('section.name')
                    ->badge(),
                TextColumn::make('students_count')
                    ->counts('students')
                    ->badge()
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
                            return !User::find(Auth::id())->can('deleteMany_classes');
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
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasses::route('/create'),
            'edit' => Pages\EditClasses::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'list',
            'create', // Serve para o 'edit também
            'deleteMany',
            'delete',
            'update',
            'showNavigation'
        ];
    }
}
