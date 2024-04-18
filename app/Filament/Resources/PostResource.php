<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Collections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->minLength(5)
                    ->maxLength(255)
                    ->required()
                    ->columnSpan('full'),

                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->html()
                    ->words(10),


                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author'),

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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
