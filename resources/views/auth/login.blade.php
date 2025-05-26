@extends('layouts.guest')

@section('title', 'Client Login')

@section('content')
<div class="container mx-auto p-4" style="max-width: 400px;">
  <h2 class="mb-4 text-center">Client Login</h2>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('auth.login') }}" method="POST" novalidate>
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input id="email" name="email" type="email"
             class="form-control @error('email') is-invalid @enderror"
             value="{{ old('email') }}" required>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-4">
      <label for="password" class="form-label">Password</label>
      <input id="password" name="password" type="password"
             class="form-control @error('password') is-invalid @enderror" required>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-success w-100">Log In</button>
  </form>

  <p class="mt-3 text-center">
    Don’t have an account?
    <a href="{{ route('signup') }}">Sign up here</a>
  </p>
</div>
@endsection
