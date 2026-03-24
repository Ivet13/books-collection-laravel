<div class='images-container'>
    @foreach ($images as $image)
        <div>
            <button class="delete-button" data-endpoint="{{ route('images_destroy', $image->filename) }}">X</button>


            <button type="button" class="js-crud-modify {{ empty((string) $image->_id) ? 'hidden' : '' }}"
                data-id="{{ (string) $image->_id }}" data-endpoint="{{ route('images_modify', (string) $image->_id) }}"
                data-alt="{{ $image->alt ?? 'prueba' }}" data-name="{{ $image->name ?? '' }}"
                data-language="{{ $image->language ?? 'es' }}" data-title="{{ $image->title ?? 'prueba' }}"
                data-configuration="{{ $image->configuration ?? '' }}">
                <x-icons.eye /></button>

            <img class="image" src="{{ route('images_thumb', $image->filename) }}" alt="{{ $image->filename }}">
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
