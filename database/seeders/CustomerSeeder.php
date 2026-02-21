<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'email' => 'ivet@test.com',
            'password' => Hash::make('password123'),
            'name' => 'Ivet',
            'about' => null,
            'deactivated_at' => null,
        ]);
    }
}
