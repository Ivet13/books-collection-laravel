<x-admin.publishers.filter :publishers="$records" />

{{-- PAGINACIÓN --}}

{{-- <div class="js-pagination">
    {{ $records->links() }}
</div> --}}

<x-admin.publishers.pagination :records="$records" />

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.publishers.item :publisher="$record" />
    @empty
        <p>No hay autores con esos filtros.</p>
    @endforelse
</div>
