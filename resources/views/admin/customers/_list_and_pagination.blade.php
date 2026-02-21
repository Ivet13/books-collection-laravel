{{-- PAGINACIÓN --}}
<div class="js-pagination">
    {{ $records->links() }}
</div>

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.customer-item :customer="$record" />
    @empty
        <p>No hay clientes con esos filtros.</p>
    @endforelse
</div>
