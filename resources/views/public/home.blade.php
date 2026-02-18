<x-public-layout>
    <x-slot:title>
        Home
    </x-slot>

    <h1>Bienvenid@ a Books Collection</h1>

    @if (auth('customer')->check())
        <p>Hola {{ auth('customer')->user()->name }} 👋</p>
        <a href="{{ route('customer.collection') }}">Ir a mi colección</a>
    @else
        <a href="{{ route('customer.login') }}">Login</a>
        <a href="{{ route('customer.register') }}">Registro</a>
    @endif
</x-public-layout>
