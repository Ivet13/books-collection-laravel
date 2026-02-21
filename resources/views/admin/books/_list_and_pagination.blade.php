{{-- PAGINACIÓN --}}
<div class="js-pagination">
    {{ $records->links() }}
</div>

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.book-item :book="$record" />
    @empty
        <p>No hay libros con esos filtros.</p>
    @endforelse
</div>
