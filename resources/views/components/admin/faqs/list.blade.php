<x-admin.faqs.filter :faqs="$records" />

{{-- PAGINACIÓN --}}

{{-- <div class="js-pagination">
    {{ $records->links() }}
</div> --}}

<x-admin.faqs.pagination :records="$records" />

{{-- LISTA --}}
<div class="js-list">
    @forelse ($records as $record)
        <x-admin.faqs.item :faq="$record" />
    @empty
        <p>No hay FAQs con esos filtros.</p>
    @endforelse
</div>
