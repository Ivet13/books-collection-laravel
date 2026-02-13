<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Books Collection')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="layout">
    <header class="header">
        <div> Kaicen formación - {{ __('admin/titles.books') }} </div>
        <div>
            <x-icon.menu />
        </div>
    </header>

    <div class="overlay" id="menuOverlay" hidden></div>

    <nav class="side-menu" id="sideMenu" aria-hidden="true">
        <ul class="side-menu__list">
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Libros</a></li>
            <li><a href="#">Géneros</a></li>
            <li><a href="#">Publishers</a></li>
        </ul>
    </nav>

    <main class="content">
        @yield('content')
    </main>
    <footer>
        <p>© {{ date('Y') }} - Un Proyecto hecho con Laravel - &#x2764 </p>
    </footer>
    @stack('scripts')
</body>

</html>
