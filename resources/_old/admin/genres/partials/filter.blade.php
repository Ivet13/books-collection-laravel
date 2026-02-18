<form class="js-filter-form" method="GET" action="{{ route('admin.genres.index') }}">
    <button type="submit" title="Aplicar filtros"><x-icon.filter-menu /></button>
    <input type="text" name="q" placeholder="Buscar género" value="{{ request('q') }}">
    <button type="button" class="js-filter-reset">Reset</button>
</form>
