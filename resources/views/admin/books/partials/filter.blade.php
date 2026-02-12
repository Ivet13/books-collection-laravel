<form method="GET" action="{{ route('admin.books.index') }}" style="display:flex; gap:10px; align-items:center;">
    <button type="submit" title="Aplicar filtros">
        <x-icon.filter-menu />
    </button>

    <input type="text" name="q" placeholder="Buscar por título o ISBN" value="{{ request('q') }}">

    <select name="author_id">
        <option value="">-- Autor --</option>
        @foreach ($authors as $author)
            <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>
                {{ $author->name }}
            </option>
        @endforeach
    </select>

    <select name="genre_id">
        <option value="">-- Género --</option>
        @foreach ($genres as $genre)
            <option value="{{ $genre->id }}" @selected(request('genre_id') == $genre->id)>
                {{ $genre->name }}
            </option>
        @endforeach
    </select>

    <select name="publisher_id">
        <option value="">-- Editorial --</option>
        @foreach ($publishers as $publisher)
            <option value="{{ $publisher->id }}" @selected(request('publisher_id') == $publisher->id)>
                {{ $publisher->name }}
            </option>
        @endforeach
    </select>

    <a href="{{ route('admin.books.index') }}">Reset</a>
</form>
