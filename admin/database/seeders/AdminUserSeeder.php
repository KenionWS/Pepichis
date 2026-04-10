<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::updateOrCreate(
            ['email' => 'admin@pepichis.local'],
            [
                'name' => 'Pepichis Admin',
                'password' => Hash::make('pepichis123'),
            ]
        );
    }
}
