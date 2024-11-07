@extends('auth.layout.web')
@section('title','Login Page')
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Login</h3>
    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
            <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-primary w-100 mb-2" type="submit">Login</button>
        <span>
            New User? <a href="{{ url('/register') }}">Register</a>
        </span>
        <span class="float-end">
            Forgot Password <a href="{{ url('/register') }}">Click Here</a>
        </span>

    </form>
</div>

@stop