<div class="lang-select-container">
    <select name="lang" id="lang" class="main-button">
        @foreach ($languages as $lang)
            <option value="{{ $lang->label }}" {{ $lang->label == app()->getLocale() ? 'selected' : '' }}>
                {{ $lang->label }}
            </option>
        @endforeach
    </select>
</div>
