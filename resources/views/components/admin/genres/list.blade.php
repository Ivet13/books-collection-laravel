<x-admin.genres.filter :genres="$records" />

{{-- PAGINACIÓN --}}

{{-- <div class="js-pagination">
    {{ $records->links() }}
</div> --}}

<x-admin.genres.pagination :records="$records" />

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.genres.item :genre="$record" />
    @empty
        <p>No hay autores con esos filtros.</p>
    @endforelse
</div>
