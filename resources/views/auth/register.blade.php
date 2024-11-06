<form action="{{ url('/register') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div>{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Register</button>
</form>