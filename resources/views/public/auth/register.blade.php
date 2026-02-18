@extends('layouts.public')
@section('title', 'Registro')

@section('content')
    <h1>Registro</h1>

    <form method="POST" action="{{ route('customer.register.store') }}">
        @csrf

        <div>
            <label>Nombre</label>
            <input name="name" value="{{ old('name') }}">
            @error('name')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Email</label>
            <input name="email" value="{{ old('email') }}">
            @error('email')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Password</label>
            <input name="password" type="password">
            @error('password')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Confirmación</label>
            <input name="password_confirmation" type="password">
        </div>

        <button type="submit">Crear cuenta</button>
    </form>
@endsection
