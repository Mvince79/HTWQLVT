<?php

namespace App\Filament\Admin\Resources\Drivers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Liên kết tài khoản')
                ->description('Chọn người dùng sẽ đóng vai trò tài xế')
                ->schema([
                    Select::make('user_id')
                        ->label('Chọn User')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Section::make('Thông tin xe & Bằng lái')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('license_number')->label('Số bằng lái')->required(),
                        TextInput::make('license_type')->label('Hạng bằng (B2, C, D...)')->required(),
                        TextInput::make('vehicle_type')->label('Loại xe')->placeholder('Ví dụ: Xe tải 5 tấn'),
                        TextInput::make('plate_number')->label('Biển số xe')->required(),
                        Select::make('working_status')
                            ->label('Trạng thái làm việc')
                            ->options([
                                'idle' => 'Đang rảnh',
                                'assigned' => 'Đã phân công',
                                'delivering' => 'Đang đi giao',
                            ])->default('idle')->required(),
                    ]),
                ]),
        ]);
    }
}
