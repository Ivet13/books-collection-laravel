<x-public.public-layout>
    <x-slot:title>
        Login
    </x-slot>

    <h1>Login customer</h1>

    <form method="POST" action="{{ route('customer.login.store') }}">
        @csrf

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

        <label>
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>

        <button type="submit">Entrar</button>
    </form>
</x-public.public-layout>
