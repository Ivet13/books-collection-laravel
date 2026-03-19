<div>
    @foreach ($images as $image)
        {{ Debugbar::info($image) }}
        <div>
            <button class="delete-button" data-endpoint="{{ route('images_destroy', (string) $image->_id) }}">X</button>


            <button type="button" class="js-crud-modify {{ empty((string) $image->_id) ? 'hidden' : '' }}"
                data-id="{{ (string) $image->_id }}" data-endpoint="{{ route('images_modify') }}">
                <x-icons.eye /></button>

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
