@extends('auth.layout.web')
@section('title','Forgot Password Page')
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Change Password</h3>
    <form action="{{ route('resetPassword.perform') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{$token}}">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" name="password_confirmation" required>
            @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button class="btn btn-primary w-100 mb-2" type="submit">Submit</button>
    </form>
</div>

@stop