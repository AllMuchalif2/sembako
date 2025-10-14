<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Administrator'
        ]);

        $customerRole = Role::create([
            'name' => 'customer',
            'description' => 'Regular Customer'
        ]);

        // 2. Create Admin User
        User::create([
            'name' => 'Admin Sembako',
            'email' => 'admin@sembako.com',
            'password' => Hash::make('aabbccdd'), // Ganti dengan password yang aman
            'role_id' => $adminRole->id,
        ]);

        // 3. Create Customer User
        User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@example.com',
            'password' => Hash::make('aabbccdd'), // Ganti dengan password yang aman
            'role_id' => $customerRole->id,
        ]);
    }
}
