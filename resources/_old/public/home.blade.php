@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <h1>Bienvenida a Books Collection</h1>

    @if (auth('customer')->check())
        <p>Hola {{ auth('customer')->user()->name }} 👋</p>
        <a href="{{ route('customer.collection') }}">Ir a mi colección</a>
    @else
        <a href="{{ route('customer.login') }}">Login</a>
        <a href="{{ route('customer.register') }}">Registro</a>
    @endif
@endsection
