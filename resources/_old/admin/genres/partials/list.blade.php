<div class="js-pagination">
    {{ $records->links() }}
</div>

<div class="js-list">
    @forelse($records as $record)
        <div class="edit-tab" data-id="{{ $record->id }}">
            <strong>{{ $record->name }}</strong>
        </div>
    @empty
        <p>No hay géneros con esos filtros.</p>
    @endforelse
</div>
