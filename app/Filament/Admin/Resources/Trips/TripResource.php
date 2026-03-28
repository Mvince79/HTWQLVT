<?php

namespace App\Filament\Admin\Resources\Trips;

use App\Filament\Admin\Resources\Trips\Pages\CreateTrip;
use App\Filament\Admin\Resources\Trips\Pages\EditTrip;
use App\Filament\Admin\Resources\Trips\Pages\ListTrips;
use App\Filament\Admin\Resources\Trips\Schemas\TripForm;
use App\Filament\Admin\Resources\Trips\Tables\TripsTable;
use App\Models\Trip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;
    protected static ?string $navigationLabel = 'Chuyến';


    public static function form(Schema $schema): Schema
    {
        return TripForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripsTable::configure($table);
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
            'index' => ListTrips::route('/'),
            'create' => CreateTrip::route('/create'),
            'edit' => EditTrip::route('/{record}/edit'),
        ];
    }
}
