<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'iTechConnect')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Your custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <style>
    .logo-img {
      height: 100px;
      max-width: 300px;
      width: auto;
    }
    /* Strength indicator colors */
    #strength-text.weak   { color: #dc3545; } /* red */
    #strength-text.medium { color: #ffc107; } /* yellow */
    #strength-text.strong { color: #28a745; } /* green */
  </style>
</head>
<body>

  <!-- Guest Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex align-items-center">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo"
             class="logo-img"
             onerror="this.onerror=null;this.src='https://via.placeholder.com/150';">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="guestNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}"
               href="{{ route('loans.index') }}">Loans</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
               href="{{ route('login') }}">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('signup') ? 'active' : '' }}"
               href="{{ route('signup') }}">Sign Up</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
  @stack('scripts')
</body>
</html>
