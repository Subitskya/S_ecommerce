@extends('auth.layout.web')
@section('title','Register Page')
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Register</h3>
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>
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
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
        <button class="btn btn-primary" type="submit">Register</button>
        <span class="float-end">
            Already have an account? <a href="{{ url('/login') }}">Login</a>
        </span>
    </form>
</div>
@stop