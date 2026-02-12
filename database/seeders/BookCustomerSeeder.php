<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Book;

class BookCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();
        $book = Book::first();

        if (!$customer || !$book) return;

        $customer->books()->syncWithoutDetaching([
            $book->id => [
                'status' => 'wishlist',
                'is_favorite' => true,
                'rating' => 5,
                'review' => 'Me encantó',
            ]
        ]);
    }
}
