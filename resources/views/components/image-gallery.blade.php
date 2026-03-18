<div>
    @foreach ($images as $image)
        {{ Debugbar::info($image) }}
        <img src="{{ route('images_thumb', $image->filename) }}" alt="{{ $image->filename }}">
    @endforeach
</div>
