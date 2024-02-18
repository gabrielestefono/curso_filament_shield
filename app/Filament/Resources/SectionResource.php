<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Classes;
use App\Models\Section;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\SectionResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Facades\Auth;

class SectionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationGroup = "Academy Management";

    public static function shouldRegisterNavigation(): bool
    {
        return User::find(Auth::id())->can('showNavigation_section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->options(
                        Classes::all()->mapWithKeys(function ($class) {
                            return [$class->id => $class->name];
                        })
                    ),
                TextInput::make('name')
                    ->unique(ignoreRecord: true, modifyRuleUsing:function(Get $get, Unique $rule){
                        return $rule->where('class_id', $get('class_id'));
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        if(!User::find(Auth::id())->can('list_section')){
            abort(403);
        }
        return $table
            ->columns([
                TextColumn::make('class.name'),
                TextColumn::make('name'),
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
                                return !User::find(Auth::id())->can('deleteMany_section');
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'list',
            'create',
            'deleteMany',
            'delete',
            'update',
            'showNavigation'
        ];
    }
}
