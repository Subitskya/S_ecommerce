@extends('auth.layout.web')
@section('title','Register Page')
@section('recaptcha')
<script src="https://www.google.com/recaptcha/api.js"></script>
@stop
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Register</h3>
    <form action="{{ url('/register') }}" method="POST" id="register-form">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password">
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" name="password_confirmation">
            @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        @error('g-recaptcha-response')
        <span class="text-danger mb-4">{{ $message }}</span>
        @enderror
        {{-- <button class="btn btn-primary" type="submit">Register</button> --}}
        <button class="btn btn-primary mt-3 g-recaptcha" data-sitekey="{{config('services.recaptcha.site')}}"
            data-callback='onSubmit' data-action='submit'>Register</button>
        <span class="float-end">
            Already have an account? <a href="{{ url('/login') }}">Login</a>
        </span>
    </form>
</div>
@stop
@push('script')
<script>
    function onSubmit(token) {
      document.getElementById("register-form").submit();
    }
</script>
@endpush