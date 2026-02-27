<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Books Collection' }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout">
    <header class="header">
        <div>{{ __('admin/titles.mainTitle') }} </div>
        <div>
            <x-icon.menu />
        </div>
    </header>

    <div class="overlay" id="menuOverlay" hidden></div>
    @auth('web')
        {{-- Admin menu --}}
        <nav class="side-menu" id="sideMenu" aria-hidden="true">
            <ul class="side-menu__list">
                <li><a href="{{ route('admin.customers.index') }}">{{ __('admin/titles.customers') }}</a></li>
                <li><a href="{{ route('admin.books.index') }}">{{ __('admin/titles.books') }}</a></li>
                <li><a href="{{ route('admin.authors.index') }}">{{ __('admin/titles.authors') }}</a></li>
                <li><a href="{{ route('admin.genres.index') }}">{{ __('admin/titles.genres') }}</a></li>
                <li><a href="{{ route('admin.publishers.index') }}">{{ __('admin/titles.publishers') }}</a></li>

                <li>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    @endauth

    <main id="app-content" class="content">
        <div class="crud-shell">
            <section class="form" id="crudForm">
                {{ $form ?? '' }}
            </section>

            <section class="table" id="crudTable">
                {{ $table ?? '' }}
            </section>
        </div>
    </main>
    <footer>
        <p>© {{ date('Y') }} - Un Proyecto hecho con Laravel - &#x2764 </p>
    </footer>

</body>

</html>
