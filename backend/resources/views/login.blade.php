<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        {{ csrf_field() }}

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>

        <div>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember me</label>
        </div>

        <div>
            <a href="{{ route('forgot-password') }}">¿Olvidaste tu contraseña?</a>
            <!-- Agregar enlace para olvidar la contraseña -->
        </div>

        <div>
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>

</html>
