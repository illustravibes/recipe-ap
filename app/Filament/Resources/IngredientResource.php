<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngredientResource\Pages;
use App\Filament\Resources\IngredientResource\RelationManagers;
use App\Models\Ingredient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngredientResource extends Resource
{
    protected static ?string $model = Ingredient::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker'; // Icon yang lebih cocok untuk ingredient

    protected static ?string $navigationGroup = 'Recipe Management'; // Mengelompokkan di navigasi yang sama

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ingredient Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('default_unit')
                            ->label('Default Unit (e.g., gram, cup, tablespoon)')
                            ->maxLength(50),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('default_unit')
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
            'index' => Pages\ListIngredients::route('/'),
            'create' => Pages\CreateIngredient::route('/create'),
            'view' => Pages\ViewIngredient::route('/{record}'),
            'edit' => Pages\EditIngredient::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Info')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Name'),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('Description'),
                                Infolists\Components\TextEntry::make('default_unit')
                                    ->label('Default Unit'),
                            ]),
                    ]),
            ]);
    }
}
