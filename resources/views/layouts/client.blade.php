<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Client Dashboard')</title>

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
  </style>
</head>
<body>

  <!-- Client Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex align-items-center">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('client.home') }}">
        <img
          src="{{ asset('images/logo.png') }}"
          alt="Logo"
          class="logo-img"
          onerror="this.onerror=null;this.src='https://via.placeholder.com/150';"
        >
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="clientNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a
              class="nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}"
              href="{{ route('client.home') }}"
            >Home</a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link {{ request()->routeIs('client.loan') ? 'active' : '' }}"
              href="{{ route('client.loan') }}"
            >Apply for Loan</a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link {{ request()->routeIs('client.loans') ? 'active' : '' }}"
              href="{{ route('client.loans') }}"
            >Loans</a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link {{ request()->routeIs('client.account') ? 'active' : '' }}"
              href="{{ route('client.account') }}"
            >Account</a>
          </li>
          <li class="nav-item">
            <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-secondary ms-3">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main class="container py-4">
    @yield('content')
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
  @stack('scripts')
</body>
</html>
