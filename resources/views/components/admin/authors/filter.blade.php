<form class="js-filter-form" method="GET" action="{{ route('admin.authors.index') }}" style="">
    <button type="submit" title="Aplicar filtros">
        <x-icons.filter-menu />
    </button>

    <input type="text" name="q" placeholder="Buscar por nombre del autor" value="{{ request('q') }}">

    <select name="author_id">
        <option value="">-- Autor --</option>
        @foreach ($authors as $author)
            <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>
                {{ $author->name }}
            </option>
        @endforeach
    </select>

    <button type="button" class="js-filter-reset">Reset</button>

</form>
