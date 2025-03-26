<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag'; // Icon yang lebih cocok untuk kategori

    protected static ?string $navigationGroup = 'Recipe Management'; // Mengelompokkan di navigasi yang sama dengan Recipe

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\RecipesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Category Information')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Name'),
                                Infolists\Components\TextEntry::make('description') // Perbaikan typo 'descriptiona'
                                    ->label('Description'),
                            ]),
                    ]),
                
                // Menambahkan daftar resep yang terkait
                Infolists\Components\Section::make('Related Recipes')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('recipes')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Recipe Name')
                                    ->url(fn ($record) => RecipeResource::getUrl('view', ['record' => $record])),
                            ])
                    ]),
            ]);
    }
}
