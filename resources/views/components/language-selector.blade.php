<div class="lang-select-container">
    <select name="lang" id="lang" class="main-button">
        @foreach ($languages as $lang)
            <option value="{{ $lang->label }}" {{ $lang->label == app()->getLocale() ? 'selected' : '' }}>
                {{ $lang->label }}
            </option>
        @endforeach
    </select>
</div>


<div class="tabs tabs--lang">
    <select name="language" id="language">
        @foreach ($languages as $language)
            <option value="{{ $language->label }}" {{ $language->label == app()->getLocale() ? 'selected' : '' }}>
                {{ $language->label }}</option>
        @endforeach
    </select>
</div>
