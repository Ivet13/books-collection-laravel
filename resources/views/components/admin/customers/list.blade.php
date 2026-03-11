<x-admin.customers.filter :customers="$records" />

{{-- PAGINACIÓN --}}

{{-- <div class="js-pagination">
    {{ $records->links() }}
</div> --}}

<x-admin.customers.pagination :records="$records" />

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.customers.item :customer="$record" />
    @empty
        <p>No hay clientes con esos filtros.</p>
    @endforelse
</div>
