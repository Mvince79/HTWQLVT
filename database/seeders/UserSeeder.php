<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();/*
User::create([
'name' => 'Admin Master',
'email' => 'admin@gmail.com',
'password' => Hash::make('12345678'),
'phone' => '0900000000',
'role' => 'admin',
'is_active' => true,
]);
User::firstOrCreate(
['email' => 'admin@gmail.com'],
[
'name' => 'Admin Master',
'password' => Hash::make('12345678'),
'phone' => '0900000000',
'role' => 'admin',
'is_active' => true,
]
);*/
    }
}
