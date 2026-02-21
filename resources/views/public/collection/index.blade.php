<x-public.public-layout>
    <x-slot:title>
        Mi colección
    </x-slot>

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
        <x-public.book-card :book="$book" />
    @empty
        <p>No tienes libros en tu colección todavía.</p>
    @endforelse

    {{ $books->links() }}
</x-public.public-layout>
