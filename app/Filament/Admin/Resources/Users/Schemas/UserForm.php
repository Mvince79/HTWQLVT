<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Thông tin tài khoản')
                    ->description('Thông tin cơ bản để đăng nhập hệ thống')
                    ->schema([
                        Grid::make(2) // Chia làm 2 cột bên trong section
                            ->schema([
                                TextInput::make('name')->required()->maxLength(255),
                                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                                TextInput::make('phone')->tel(),
                                Select::make('role')
                                    ->options([
                                        'admin' => 'Quản trị viên',
                                        'driver' => 'Tài xế',
                                    ])->required(),
                            ]),
                    ]),

                Section::make('Bảo mật & Trạng thái')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn($context) => $context === 'create')
                            ->dehydrated(fn($password) => filled($password)),
                        Toggle::make('is_active')
                            ->label('Kích hoạt tài khoản')
                            ->default(true),
                    ]),
            ]);
    }
}
