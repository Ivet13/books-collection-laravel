@extends('layouts.public')
@section('title', 'Mi colección')

@section('content')
    <h1>Mi colección</h1>

    <form method="GET" action="{{ route('customer.collection') }}" style="display:flex; gap:10px; align-items:center;">
        <input name="q" placeholder="Buscar título o ISBN" value="{{ request('q') }}">

        <select name="status">
            <option value="">-- Estado --</option>
            <option value="wishlist" @selected(request('status') === 'wishlist')>Wishlist</option>
            <option value="reading" @selected(request('status') === 'reading')>Reading</option>
            <option value="read" @selected(request('status') === 'read')>Read</option>
        </select>

        <select name="favorite">
            <option value="">-- Favoritos --</option>
            <option value="1" @selected(request('favorite') === '1')>Solo favoritos</option>
            <option value="0" @selected(request('favorite') === '0')>No favoritos</option>
        </select>

        <button type="submit">Filtrar</button>
        <a href="{{ route('customer.collection') }}">Reset</a>
    </form>

    <hr>

    @forelse($books as $book)
        <div class="book-card">

            <h3>{{ $book->title }}</h3>

            <div class="book-meta">
                ISBN: {{ $book->isbn ?? '—' }}
            </div>

            <div class="book-section">
                <strong>Estado:</strong>
                <span class="status status-{{ $book->pivot->status }}">
                    {{ ucfirst($book->pivot->status) }}
                </span>
            </div>

            <div class="book-section">
                <strong>Favorito:</strong>
                @if ($book->pivot->is_favorite)
                    <span class="favorite">⭐ Sí</span>
                @else
                    No
                @endif
            </div>

            <div class="book-section">
                <strong>Rating:</strong>
                {{ $book->pivot->rating ?? '—' }}
            </div>

            @if ($book->pivot->review)
                <div class="review-box">
                    {{ $book->pivot->review }}
                </div>
            @endif

        </div>

    @empty
        <p>No tienes libros en tu colección todavía.</p>
    @endforelse

    {{ $books->links() }}
@endsection
