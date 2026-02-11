<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookCustomerController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $customer = auth()->user();
        // o auth('customer')->user() si usas guard separado

        $data = $request->validate([
            'status' => 'required|in:wishlist,reading,read',
            'is_favorite' => 'sometimes|boolean',
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $customer->books()->syncWithoutDetaching([
            $book->id => [
                'status' => $data['status'],
                'is_favorite' => $data['is_favorite'] ?? false,
                'rating' => $data['rating'] ?? null,
                'review' => $data['review'] ?? null,
            ]
        ]);

        return back()->with('success', 'Libro actualizado en tu colección');
    }

    public function destroy(Book $book)
    {
        $customer = auth()->user();

        $customer->books()->detach($book->id);

        return back()->with('success', 'Libro eliminado de tu colección');
    }
}
