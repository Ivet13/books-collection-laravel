@props(['book'])

<div class="book-card">

    <h3>{{ $book->title }}</h3>

    <div class="book-meta">
        ISBN: {{ $book->isbn ?? '—' }}
    </div>

    <div class="book-section">
        <strong>Estado:</strong>
        <span class="status status-{{ $book->pivot->status }}">
            {{ ucfirst($book->pivot->status) }}
        </span>
    </div>

    <div class="book-section">
        <strong>Favorito:</strong>
        @if ($book->pivot->is_favorite)
            <span class="favorite">⭐ Sí</span>
        @else
            No
        @endif
    </div>

    <div class="book-section">
        <strong>Rating:</strong>
        {{ $book->pivot->rating ?? '—' }}
    </div>

    @if ($book->pivot->review)
        <div class="review-box">
            {{ $book->pivot->review }}
        </div>
    @endif

</div>
