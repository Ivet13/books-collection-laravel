<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerCollectionController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            abort(401, 'Unauthorized');
        }

        $request->validate([
            'status' => 'nullable|in:wishlist,reading,read',
            'favorite' => 'nullable|in:0,1',
            'q' => 'nullable|string|max:255',
        ]);

        $booksQuery = $customer->books()
            ->with(['authors', 'genres', 'bookPublisher.publisher'])
            ->when(
                $request->filled('status'),
                fn($q) =>
                $q->wherePivot('status', $request->status)
            )
            ->when(
                $request->filled('favorite'),
                fn($q) =>
                $q->wherePivot('is_favorite', (int)$request->favorite)
            )
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->string('q')->toString();
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', "%{$term}%")
                        ->orWhere('isbn', 'like', "%{$term}%");
                });
            })
            ->orderBy('book_customer.updated_at', 'desc');

        $books = $booksQuery->paginate(10)->withQueryString();

        return view('public.collection.index', compact('books', 'customer'));
    }
}
