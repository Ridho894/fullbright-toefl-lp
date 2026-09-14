<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@fullbright.id'],
            [
                'name' => 'Admin Fullbright',
                'password' => Hash::make('Fullbright#Admin2026'),
                'role' => 'admin',
            ],
        );
    }
}
