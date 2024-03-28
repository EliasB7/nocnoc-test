<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>

<body>
    <h2>Forgot Password</h2>
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('forgot-password-post') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Send Password Reset Link</button>
    </form>
</body>

</html>
