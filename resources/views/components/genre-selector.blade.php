<div class="genre-select-container">
    <select name="genre" id="genre" class="main-button">
        @foreach ($genres as $genre)
            <option value="{{ $genre->name }}">
                {{ $genre->name }}
            </option>
        @endforeach
    </select>
</div>
