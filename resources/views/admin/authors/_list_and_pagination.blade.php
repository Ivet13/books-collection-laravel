{{-- PAGINACIÓN --}}
<div class="js-pagination">
    {{ $records->links() }}
</div>

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-author-item :author="$record" />
    @empty
        <p>No hay autores con esos filtros.</p>
    @endforelse
</div>
