<x-app-layout title="Autores | Admin">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="authors-page">
        <aside class="authors-left">
            {{-- We might need a specific filter for authors, or use the generic one if it fits. 
                 The legacy view included 'admin.authors.partials.filter'. 
                 Assuming we might need to migrate that too or if the generic one works.
                 For now, I'll use a placeholder or check if generic filter works. 
                 The generic filter has slots for authors, genres, publishers. 
                 On authors page, we probably filter by name (q).
            --}}
            {{-- @todo: Create/Use author specific filter if needed. For now using a simple form or checking if generic filter can be adapted. 
                 The generic filter expects $authors, $genres, $publishers which might not be present here.
                 I will create a simple search form here to match legacy functionality or import if available.
            --}}
            <form class="js-filter-form" method="GET" action="{{ route('admin.authors.index') }}">
                <div class="search-box">
                    <input type="text" name="q" placeholder="Buscar autor..." value="{{ request('q') }}">
                    <button type="submit">Buscar</button>
                </div>
            </form>

            {{-- lista + paginación --}}
            <div class="js-list">
                @forelse ($records as $record)
                    <x-author-item :author="$record" />
                @empty
                    <p>No hay autores.</p>
                @endforelse

                <div class="js-pagination">
                    {{ $records->links() }}
                </div>
            </div>
        </aside>

        <main class="authors-right">
            <x-author-form />
        </main>
    </div>

    @push('scripts')
        <style>
            .authors-page {
                display: grid;
                grid-template-columns: 1fr 420px;
                gap: 16px;
            }

            .edit-tab {
                cursor: pointer;
            }

            .edit-tab.selected {
                outline: 2px solid blue;
            }
        </style>
    @endpush
</x-app-layout>
