@extends('auth.layout.web')
@section('title','Forgot Password Page')
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Forgot Password</h3>
    <p>Enter your email and we'll send you a link to reset your password.</p>
    <form action="{{ route('forgotPassword.perform') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button class="btn btn-primary w-100 mb-2" type="submit">Submit</button>
        <span>
            New User ? <a href="{{ url('/register') }}">Register</a>
        </span>
        <span class="float-end">
            Member ? <a href="{{ url('/login') }}">Login</a>
        </span>

    </form>
</div>

@stop