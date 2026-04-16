<?php

namespace App\Filament\Admin\Resources\FuelLogs;

use App\Filament\Admin\Resources\FuelLogs\Pages\CreateFuelLog;
use App\Filament\Admin\Resources\FuelLogs\Pages\EditFuelLog;
use App\Filament\Admin\Resources\FuelLogs\Pages\ListFuelLogs;
use App\Filament\Admin\Resources\FuelLogs\Pages\ViewFuelLog;
use App\Filament\Admin\Resources\FuelLogs\Schemas\FuelLogForm;
use App\Filament\Admin\Resources\FuelLogs\Schemas\FuelLogInfolist;
use App\Filament\Admin\Resources\FuelLogs\Tables\FuelLogsTable;
use App\Models\FuelLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuelLogResource extends Resource
{
    protected static ?string $model = FuelLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Fire;

    protected static ?string $navigationLabel = 'Nhiên liệu';

    public static function getModelLabel(): string
    {
        return 'Nhiên liệu';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Danh sách nhiên liệu';
    }


    public static function form(Schema $schema): Schema
    {
        return FuelLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FuelLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuelLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFuelLogs::route('/'),
            'create' => CreateFuelLog::route('/create'),
            'view' => ViewFuelLog::route('/{record}'),
            'edit' => EditFuelLog::route('/{record}/edit'),
        ];
    }
}