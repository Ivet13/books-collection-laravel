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

<body class="public-layout">
    <header>
        <nav>
            <a href="/">Home</a>
            @if (auth('customer')->check())
                | <a href="{{ route('customer.collection') }}">Mi Colección</a>
                | <form method="POST" action="{{ route('customer.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                | <a href="{{ route('customer.login') }}">Login</a>
                | <a href="{{ route('customer.register') }}">Registro</a>
                | <a href="{{ route('admin.login') }}">Admin</a>
            @endif
            <x-language-selector />
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Books Collection</p>
    </footer>
</body>

</html>
