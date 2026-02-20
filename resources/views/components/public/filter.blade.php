<form class="js-filter-form" method="GET" action="{{ route('admin.books.index') }}" style="">
    <button type="submit" title="Aplicar filtros">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <title>filter-menu</title>
                <path
                    d="M11 11L16.76 3.62A1 1 0 0 0 16.59 2.22A1 1 0 0 0 16 2H2A1 1 0 0 0 1.38 2.22A1 1 0 0 0 1.21 3.62L7 11V16.87A1 1 0 0 0 7.29 17.7L9.29 19.7A1 1 0 0 0 10.7 19.7A1 1 0 0 0 11 18.87V11M13 16L18 21L23 16Z" />
            </svg>
        </span>

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

    <button type="button" class="js-filter-reset">Reset</button>

</form>
