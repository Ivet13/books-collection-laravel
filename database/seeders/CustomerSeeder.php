<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'email' => 'ivet@test.com',
            'password' => bcrypt('secret'),
            'name' => 'Ivet',
            'about' => null,
            'deactivated_at' => null,
        ]);
    }
}
