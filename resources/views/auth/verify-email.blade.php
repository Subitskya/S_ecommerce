@extends('auth.layout.web')
@section('title','Verify Email Address')
@section('content')
<div class="card w-50 p-3 mt-3" style="margin: 0 auto;">
    <h3>Verify Email Address</h3>
    <p>Verify your email address in order to proceed to checkout</p>
    <form action="{{ route('verifyEmail.perform') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button class="btn btn-primary w-100 mb-2" type="submit">Verify your Email</button>
        <span>
            Already verified Email Address? <a href="{{ route('login') }}">Click Here</a>
        </span>

    </form>
</div>

@stop