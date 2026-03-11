<x-admin.authors.filter :authors="$records" />

{{-- PAGINACIÓN --}}

{{-- <div class="js-pagination">
    {{ $records->links() }}
</div> --}}

<x-admin.authors.pagination :records="$records" />

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.authors.item :author="$record" />
    @empty
        <p>No hay autores con esos filtros.</p>
    @endforelse
</div>
