<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Collections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->minLength(5)
                    ->maxLength(255)
                    ->required()
                    ->columnSpan('full'),

                Forms\Components\TextInput::make('description')
                    ->maxLength(100)
                    ->columnSpan('full'),

                Forms\Components\TextInput::make('mandays')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Task $record): string => $record->description)
                    ->searchable(),

                Tables\Columns\TextColumn::make('mandays')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->formatStateUsing(
                        function (string $state) {
                            return Carbon::parse($state)->tz(config('app.local_tz'))
                                ->isoFormat('MMMM Do YYYY, HH:mm:ss');
                        }
                    ),

                Tables\Columns\TextColumn::make('updated_at')
                    ->formatStateUsing(
                        function (string $state) {
                            return Carbon::parse($state)->tz(config('app.local_tz'))
                                ->isoFormat('MMMM Do YYYY, HH:mm:ss');
                        }
                    )
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
