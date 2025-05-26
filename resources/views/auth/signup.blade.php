@extends('layouts.guest')

@section('title', 'Client Sign Up')

@section('content')
<div class="container mx-auto p-4" style="max-width: 480px;">
  <h2 class="mb-4 text-center">Client Sign Up</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('auth.register') }}" method="POST" novalidate>
    @csrf

    <!-- Full Name -->
    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input id="name" name="name" type="text"
             class="form-control @error('name') is-invalid @enderror"
             placeholder="e.g. Juan dela R. Cruz"
             value="{{ old('name') }}" required>
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Employee ID -->
    <div class="mb-3">
      <label for="employee_id" class="form-label">Employee ID</label>
      <input id="employee_id" name="employee_id" type="text"
             class="form-control @error('employee_id') is-invalid @enderror"
             placeholder="e.g. EMP-1234"
             value="{{ old('employee_id') }}" required>
      @error('employee_id')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Date of Birth -->
    <div class="mb-3">
      <label for="dob" class="form-label">Date of Birth</label>
      <input id="dob" name="dob" type="date"
             class="form-control @error('dob') is-invalid @enderror"
             value="{{ old('dob') }}" required>
      @error('dob')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Address -->
    <div class="mb-3">
      <label for="address" class="form-label">Complete Address</label>
      <textarea id="address" name="address" rows="2"
                class="form-control @error('address') is-invalid @enderror"
                placeholder="e.g. 123 Main St, Barangay San Isidro, Tuguegarao City, Cagayan, Region II"
                required>{{ old('address') }}</textarea>
      @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email Address</label>
      <input id="email" name="email" type="email"
             class="form-control @error('email') is-invalid @enderror"
             placeholder="you@example.com"
             value="{{ old('email') }}" required>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Password & Strength -->
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input id="password" name="password" type="password"
             class="form-control @error('password') is-invalid @enderror"
             placeholder="At least 6 characters" required>
      <div id="strength-text" class="form-text mt-1"></div>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Password Confirmation -->
    <div class="mb-4">
      <label for="password_confirmation" class="form-label">Confirm Password</label>
      <input id="password_confirmation" name="password_confirmation" type="password"
             class="form-control" placeholder="Re-enter password" required>
      <div class="invalid-feedback" id="password-error">Passwords do not match.</div>
    </div>

    <button id="submit-btn" type="submit" class="btn btn-primary w-100" disabled>
      Sign Up
    </button>
  </form>

  <p class="mt-3 text-center">
    Already have an account?
    <a href="{{ route('login') }}">Log in here</a>
  </p>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const pwd       = document.getElementById('password');
  const confirm   = document.getElementById('password_confirmation');
  const strength  = document.getElementById('strength-text');
  const errorDiv  = document.getElementById('password-error');
  const submitBtn = document.getElementById('submit-btn');

  // Hide feedback initially
  strength.textContent = '';
  errorDiv.style.display = 'none';

  function checkStrength() {
    const val = pwd.value;
    if (val.length < 6) {
      strength.textContent = 'Weak';
      strength.className = 'form-text weak';
    } else if (val.length < 10) {
      strength.textContent = 'Medium';
      strength.className = 'form-text medium';
    } else {
      strength.textContent = 'Strong';
      strength.className = 'form-text strong';
    }
  }

  function validateForm() {
    const match = pwd.value === confirm.value && confirm.value.length > 0;
    if (match) {
      confirm.classList.add('is-valid');
      confirm.classList.remove('is-invalid');
      errorDiv.style.display = 'none';
      submitBtn.disabled = false;
    } else {
      confirm.classList.add('is-invalid');
      confirm.classList.remove('is-valid');
      errorDiv.style.display = 'block';
      submitBtn.disabled = true;
    }
  }

  // Events
  pwd.addEventListener('input', () => {
    checkStrength();
    validateForm();
  });
  confirm.addEventListener('input', validateForm);
});
</script>
@endpush
