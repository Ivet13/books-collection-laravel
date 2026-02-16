<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Books Collection')</title>
</head>

<body>
    <nav style="display:flex; gap:12px;">
        <a href="{{ route('home') }}">Home</a>

        @if (auth('customer')->check())
            <a href="{{ route('customer.collection') }}">Mi colección</a>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit">Salir</button>
            </form>
        @else
            <a href="{{ route('customer.login') }}">Login</a>
            <a href="{{ route('customer.register') }}">Registro</a>
        @endif
    </nav>

    <hr>

    @yield('content')
</body>

</html>
