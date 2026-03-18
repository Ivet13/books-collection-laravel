<div>
    @foreach ($images as $image)
        {{ Debugbar::info($image) }}
        <div>
            <button class="delete-button" data-endpoint="{{ route('images_destroy', $image->filename) }}">X</button>
            <button class="modify-button" data-endpoint="{{ route('images_destroy', $image->filename) }}">MODIFY</button>
            <img src="{{ route('images_thumb', $image->filename) }}" alt="{{ $image->filename }}">
        </div>
    @endforeach
</div>

<style>
    .delete-button {
        position: relative;
        top: 0;
        right: 0;
        background-color: black;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }
</style>
