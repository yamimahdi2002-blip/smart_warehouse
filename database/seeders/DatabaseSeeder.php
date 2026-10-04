<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ۱. اجرای سیدر نقش‌ها
        $this->call(RoleSeeder::class);

        // گرفتن ID نقش مدیر ارشد
        $adminRole = Role::where('name', 'مدیر ارشد')->first();

        // ۲. ساخت یک کاربر مدیر ارشد پیش‌فرض
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'مدیر سیستم',
                'personnel_code' => '1001',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );
    }
}
