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

        // 1. Create Roles with explicit IDs
        $ownerRole = Role::create([
            'id' => 1,
            'name' => 'owner',
            'description' => 'Owner of the store'
        ]);
        $adminRole = Role::create([
            'id' => 2,
            'name' => 'admin',
            'description' => 'Administrator'
        ]);

        $customerRole = Role::create([
            'id' => 3,
            'name' => 'customer',
            'description' => 'Regular Customer'
        ]);

        // 2. Create User

        User::create([
            'name' => 'Owner Toko',
            'email' => 'owner@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role_id' => $ownerRole->id,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@example.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role_id' => $customerRole->id,
        ]);
    }
}
