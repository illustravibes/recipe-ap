<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationDocumentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ApplicationDocument;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ApplicationDocumentResource extends Resource
{
    protected static ?string $model = ApplicationDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('User Information')
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $users = User::where('name', $state)->get();

                                    if ($users->count() === 1) {
                                        $user = $users->first();
                                        $set('selected_user', $user->id);
                                        $set('email', $user->email);
                                        $set('role', $user->role);
                                        $set('user_options', []);
                                    } else {
                                        $options = [];
                                        foreach ($users as $user) {
                                            $options[$user->id] = $user->name;
                                        }
                                        $set('email', null);
                                        $set('role', null);
                                        $set('user_options', $options);
                                        $set('selected_user', null);
                                    }
                                }),

                            Select::make('selected_user')
                                ->label('Select User')
                                ->options([])
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $user = User::find($state);
                                        if ($user) {
                                            $set('name', $user->name);
                                            $set('email', $user->email);
                                            $set('role', $user->role);
                                        }
                                    }
                                })
                                ->hidden(fn (callable $get) => empty($get('user_options'))),

                            TextInput::make('email')
                                ->label('Email')
                                ->required(),
                        ]),
                    Wizard\Step::make('Upload Document')
                        ->schema([
                            FileUpload::make('file_path')
                                ->label('Upload Document')
                                ->disk('public')
                                ->directory('documents')
                                ->required(),
                        ]),
                ])->extraAttributes(['class' => 'w-full'])
                ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('file_path')->label('File Path'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicationDocuments::route('/'),
            'create' => Pages\CreateApplicationDocument::route('/create'),
            'edit' => Pages\EditApplicationDocument::route('/{record}/edit'),
        ];
    }
}
