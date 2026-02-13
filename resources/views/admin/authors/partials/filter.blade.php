<form class="js-filter-form" method="GET" action="{{ route('admin.authors.index') }}">
    <button type="submit" title="Aplicar filtros"><x-icon.filter-menu /></button>
    <input type="text" name="q" placeholder="Buscar autor" value="{{ request('q') }}">
    <button type="button" class="js-filter-reset">Reset</button>
</form>
