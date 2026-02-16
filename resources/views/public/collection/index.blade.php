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
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <strong>{{ $book->title }}</strong>
            <div>ISBN: {{ $book->isbn ?? '—' }}</div>

            <div>
                Estado: <strong>{{ $book->pivot->status }}</strong>
                · Favorito: {{ $book->pivot->is_favorite ? 'Sí' : 'No' }}
                · Rating: {{ $book->pivot->rating ?? '—' }}
            </div>

            @if ($book->pivot->review)
                <div>Review: {{ $book->pivot->review }}</div>
            @endif
        </div>
    @empty
        <p>No tienes libros en tu colección todavía.</p>
    @endforelse

    {{ $books->links() }}
@endsection
