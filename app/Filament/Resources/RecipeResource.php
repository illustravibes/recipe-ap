<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Filament\Resources\RecipeResource\RelationManagers;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Models\Category;
use App\Models\Ingredient;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationGroup = 'Recipe Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\FileUpload::make('attachment')
                            ->label('Recipe Image')
                            ->image()
                            ->directory('recipes')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('instructions')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('cooking_time')
                            ->label('Cooking Time (minutes)')
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('serving_size')
                            ->label('Serving Size')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ingredients')
                    ->schema([
                        Forms\Components\Repeater::make('ingredients')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('id')  // Change this from 'ingredient_id' to 'id'
                                    ->label('Ingredient')
                                    ->options(Ingredient::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('default_unit')
                                            ->label('Default Unit')
                                            ->maxLength(50),
                                    ])
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $ingredient = Ingredient::find($state);
                                        if ($ingredient) {
                                            $set('unit', $ingredient->default_unit);
                                        }
                                    }),
                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Amount')
                                    ->required(),
                                Forms\Components\TextInput::make('unit')
                                    ->label('Unit')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Textarea::make('secret_instructions')
                            ->label('Secret Recipe Instructions')
                            ->helperText('This will be encrypted and only visible to authorized users')
                            ->columnSpan('full'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cooking_time')
                    ->label('Cooking Time')
                    ->formatStateUsing(fn(int $state): string => "{$state} minutes")
                    ->sortable(),
                Tables\Columns\TextColumn::make('serving_size')
                    ->label('Serves')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('has_secret')
                    ->label('Secret Recipe')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            RelationManagers\IngredientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'view' => Pages\ViewRecipe::route('/{record}'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Recipe Details')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Name'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label('Category'),
                                Infolists\Components\TextEntry::make('cooking_time')
                                    ->label('Cooking Time')
                                    ->formatStateUsing(fn($state) => "{$state} minutes"),
                                Infolists\Components\TextEntry::make('serving_size')
                                    ->label('Serving Size')
                                    ->formatStateUsing(fn($state) => "{$state} people"),
                            ]),

                        Infolists\Components\ImageEntry::make('image_path')
                            ->label('Recipe Image')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('instructions')
                            ->label('Instructions')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Ingredients')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('ingredients')
                            ->schema([
                                Infolists\Components\Grid::make(3)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('ingredient.name')
                                            ->label('Ingredient'),
                                        Infolists\Components\TextEntry::make('amount')
                                            ->label('Amount'),
                                        Infolists\Components\TextEntry::make('unit')
                                            ->label('Unit'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
