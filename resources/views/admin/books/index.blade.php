<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>

<body>
    <header>
        <div> Kaicen formación - BOOKS </div>
        <div>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <title>menu</title>
                    <path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" />
                </svg>
            </span>
        </div>
    </header>

    <main>
        <div class="table-content">

            <div class="table-menu">
                {{-- FILTROS (GET) --}}
                <form method="GET" action="{{ route('admin.books.index') }}"
                    style="display:flex; gap:10px; align-items:center;">
                    <button type="submit" title="Aplicar filtros">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <title>filter-menu</title>
                                <path
                                    d="M11 11L16.76 3.62A1 1 0 0 0 16.59 2.22A1 1 0 0 0 16 2H2A1 1 0 0 0 1.38 2.22A1 1 0 0 0 1.21 3.62L7 11V16.87A1 1 0 0 0 7.29 17.7L9.29 19.7A1 1 0 0 0 10.7 19.7A1 1 0 0 0 11 18.87V11M13 16L18 21L23 16Z" />
                            </svg>
                        </span>
                    </button>

                    <input type="text" name="q" placeholder="Buscar por título o ISBN"
                        value="{{ request('q') }}">

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

                {{-- PAGINACIÓN (simple) --}}
                <div>
                    {{ $records->links() }}
                </div>
            </div>

            <div class="table-content">
                @forelse ($records as $record)
                    <table>
                        <tr>
                            <th>ID:</th>
                            <td>{{ $record->id }}</td>
                        </tr>
                        <tr>
                            <th>Título:</th>
                            <td>{{ $record->title }}</td>
                        </tr>
                        <tr>
                            <th>ISBN:</th>
                            <td>{{ $record->isbn }}</td>
                        </tr>
                        <tr>
                            <th>Sinopsis:</th>
                            <td>{{ $record->description }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de creación:</th>
                            <td>{{ $record->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de actualización:</th>
                            <td>{{ $record->updated_at }}</td>
                        </tr>
                    </table>
                @empty
                    <p>No hay libros con esos filtros.</p>
                @endforelse
            </div>
        </div>

        {{-- Panel derecho: ahora lo adapto a BOOK --}}
        <div class="main-content">
            <form method="POST" action="{{ route('admin.books.store') }}">
                @csrf

                <div class="form-options">
                    <div class="tabs">
                        <button type="button">GENERAL</button>
                    </div>

                    <div class="buttons">
                        {{-- Crear libro --}}
                        <button type="submit" title="Guardar">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <title>content-save</title>
                                    <path
                                        d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z" />
                                </svg>
                            </span>
                        </button>

                        {{-- Limpiar formulario --}}
                        <button type="reset" title="Limpiar">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <title>broom</title>
                                    <path
                                        d="M19.36,2.72L20.78,4.14L15.06,9.85C16.13,11.39 16.28,13.24 15.38,14.44L9.06,8.12C10.26,7.22 12.11,7.37 13.65,8.44L19.36,2.72M5.93,17.57C3.92,15.56 2.69,13.16 2.35,10.92L7.23,8.83L14.67,16.27L12.58,21.15C10.34,20.81 7.94,19.58 5.93,17.57Z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="form-fields">
                    <div>
                        <label for="title">Título</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            placeholder="El imperio del vampiro">
                        @error('title')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="isbn">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                            placeholder="978...">
                        @error('isbn')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description">Sinopsis</label>
                        <textarea name="description" id="description" placeholder="...">{{ old('description') }}</textarea>
                        @error('description')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </main>

    <footer>
    </footer>
</body>

</html>
