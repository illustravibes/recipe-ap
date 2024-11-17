<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use App\Models\Location;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Map::make('location')
                    ->label('Location')
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($set, ?array $state) {
                        $set('latitude', $state['lat'] ?? null);
                        $set('longitude', $state['lng'] ?? null);
                    })
                    ->afterStateHydrated(function ($state, $record, $set) {
                        if ($record) {
                            // Pastikan data dalam JSON di-parse dengan benar
                            $locationData = is_array($record->location) ? $record->location : json_decode($record->location, true);
                            if (is_array($locationData)) {
                                $set('location', $locationData);
                            }
                        }
                    })
                    ->extraStyles([
                        'min-height: 50vh', // Adjusting the map height to 50vh
                    ])
                    ->extraAttributes(['class' => 'custom-marker'])
                    ->liveLocation(true)
                    ->showMarker(true)
                    ->markerColor("#22c55eff")
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->zoom(15)
                    ->detectRetina()
                    ->showMyLocationButton()
                    ->geoMan(true)
                    ->geoManEditable(true)
                    ->geoManPosition('topleft')
                    ->rotateMode()
                    ->drawMarker()
                    ->drawPolygon()
                    ->drawPolyline(false)
                    ->drawCircle(false)
                    ->dragMode()
                    ->cutPolygon(false)
                    ->editPolygon(false)
                    ->deleteLayer(true)
                    ->setColor('#3388ff')
                    ->setFilledColor('#cad9ec'),

                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('location', [
                            'lat' => $state,
                            'lng' => $get('longitude') ?? null,
                        ]);
                    }),

                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('location', [
                            'lat' => $get('latitude') ?? null,
                            'lng' => $state,
                        ]);
                    }),

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('latitude'),
                Tables\Columns\TextColumn::make('longitude'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
