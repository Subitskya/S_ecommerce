<form action="{{ url('/login') }}" method="POST">
    @csrf
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
    <button type="submit">Login</button>
</form>