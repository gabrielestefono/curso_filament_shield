<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\StudentsExport;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\StudentResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StudentResource extends Resource  implements HasShieldPermissions
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = "Academy Management";

    public static function shouldRegisterNavigation(): bool
    {
        return User::find(Auth::id())->can('showNavigation_student');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('class_id')
                    ->live()
                    ->relationship(name: 'class',titleAttribute: 'name'),
                Select::make('section_id')
                    ->options(
                        function(Get $get){
                            $classId = $get('class_id');
                            if($classId){
                                return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                            }
                        }
                    ),
                TextInput::make('name')
                    ->required()
                    ->autofocus(),
                TextInput::make('email')
                    ->required()
                    ->unique(),
            ]);
    }

    public static function table(Table $table): Table
    {
        if(!User::find(Auth::id())->can('list_student')){
            abort(403);
        }
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.name')
                    ->badge()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('class_section_filter')
                ->form([
                    Select::make('class_id')
                        ->label('Filter by Class')
                        ->placeholder('Select a Class')
                        ->options(Classes::pluck('name', 'id')->toArray()),
                    Select::make('section_id')
                        ->label('Filter by Sections')
                        ->placeholder('Select a Section')
                        ->options(function(Get $get){
                            $classId = $get('class_id');
                            if($classId){
                                return Section::where('class_id', $classId)
                                    ->pluck('name', 'id')->toArray();
                            }
                        }),
                ])->query(function(Builder $query, array $data): Builder{
                    return $query->when($data['class_id'], function ($query) use ($data){
                        return $query->where('class_id', $data['class_id']);
                    })->when($data['section_id'], function ($query) use ($data){
                        return $query->where('section_id', $data['section_id']);
                    });
                })
                ->hidden(
                    function(){
                        return !User::find(Auth::id())->can('filter_student');
                    }
                ),
            ])
            ->actions([
                Action::make('downloadPdf')
                    ->hidden(
                        function(){
                            return !User::find(Auth::id())->can('download_student');
                        }
                    )
                    ->url(function(Student $student){
                        return route('student.invoice.generate', $student);
                    }),
                Action::make('qrCode')
                    ->url(function(Student $student){
                        return static::getUrl('qrCode', ['record' => $student]);
                    })
                    ->hidden(
                        function(){
                            return !User::find(Auth::id())->can('qrCode_student');
                        }
                    )
                    ,
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(
                            function(){
                                return !User::find(Auth::id())->can('deleteMany_student');
                            }
                        ),
                    BulkAction::make('export')
                            ->label('Export Records')
                            ->icon('heroicon-o-document-arrow-down')
                            ->action(function (Collection $records) {
                                return Excel::download(new StudentsExport($records), 'students.xlsx');
                    })->hidden(
                        function(){
                            return !User::find(Auth::id())->can('export_student');
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'qrCode' => Pages\GenerateQrCode::route('/{record}/qrcode'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'list',
            'download',
            'create',
            'update',
            'delete',
            'deleteMany',
            'qrCode',
            'filter',
            'export',
            'showNavigation'
        ];
    }
}
