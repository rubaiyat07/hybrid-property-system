<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '01700000000',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('Admin');

        // Landlord user
        $landlord = User::create([
            'name' => 'John Landlord',
            'email' => 'landlord@example.com',
            'phone' => '01711111111',
            'password' => Hash::make('password123'),
        ]);
        $landlord->assignRole('Landlord');

        // Tenant user
        $tenant = User::create([
            'name' => 'Jane Tenant',
            'email' => 'tenant@example.com',
            'phone' => '01722222222',
            'password' => Hash::make('password123'),
        ]);
        $tenant->assignRole('Tenant');

        // Agent user
        $agent = User::create([
            'name' => 'Alex Agent',
            'email' => 'agent@example.com',
            'phone' => '01733333333',
            'password' => Hash::make('password123'),
        ]);
        $agent->assignRole('Agent');

        // Buyer user
        $buyer = User::create([
            'name' => 'Brian Buyer',
            'email' => 'buyer@example.com',
            'phone' => '01744444444',
            'password' => Hash::make('password123'),
        ]);
        $buyer->assignRole('Buyer');

        // Maintenance user
        $maintenance = User::create([
            'name' => 'Mark Maintenance',
            'email' => 'maintenance@example.com',
            'phone' => '01755555555',
            'password' => Hash::make('password123'),
        ]);
        $maintenance->assignRole('Maintenance');
    }
}
