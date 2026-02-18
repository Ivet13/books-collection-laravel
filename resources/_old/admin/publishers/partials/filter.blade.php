<form class="js-filter-form" method="GET" action="{{ route('admin.publishers.index') }}">
    <button type="submit" title="Aplicar filtros"><x-icon.filter-menu /></button>
    <input type="text" name="q" placeholder="Buscar editorial" value="{{ request('q') }}">
    <button type="button" class="js-filter-reset">Reset</button>
</form>
