<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecipesRelationManager extends RelationManager
{
    protected static string $relationship = 'recipes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(1000),
                Forms\Components\Textarea::make('instructions')
                    ->required(),
                Forms\Components\FileUpload::make('attachment')
                    ->label('Recipe Image')
                    ->image()
                    ->directory('recipes')
                    ->maxSize(5120),
                Forms\Components\TextInput::make('cooking_time')
                    ->label('Cooking Time (minutes)')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('serving_size')
                    ->label('Serving Size')
                    ->numeric()
                    ->minValue(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cooking_time')
                    ->label('Cooking Time')
                    ->formatStateUsing(fn($state): string => $state ? "{$state} minutes" : "-")
                    ->sortable(),
                Tables\Columns\TextColumn::make('serving_size')
                    ->label('Serves')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
