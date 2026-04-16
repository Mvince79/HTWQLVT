<?php

namespace App\Filament\Admin\Resources\Vehicles;

use App\Filament\Admin\Resources\Vehicles\Pages\CreateVehicle;
use App\Filament\Admin\Resources\Vehicles\Pages\EditVehicle;
use App\Filament\Admin\Resources\Vehicles\Pages\ListVehicles;
use App\Filament\Admin\Resources\Vehicles\Pages\ViewVehicle;
use App\Filament\Admin\Resources\Vehicles\Schemas\VehicleForm;
use App\Filament\Admin\Resources\Vehicles\Schemas\VehicleInfolist;
use App\Filament\Admin\Resources\Vehicles\Tables\VehiclesTable;
use App\Models\Vehicle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;
    protected static ?string $navigationLabel = 'Phương tiện';

    public static function getModelLabel(): string
    {
        return 'Phương tiện';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Danh sách phương tiện';
    }


    protected static ?string $recordTitleAttribute = 'license_plate';

    public static function form(Schema $schema): Schema
    {
        return VehicleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VehicleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehiclesTable::configure($table);
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
            'index' => ListVehicles::route('/'),
            'view' => ViewVehicle::route('/{record}'),
        ];
    }
}
