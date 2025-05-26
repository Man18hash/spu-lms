<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bookkeeper Dashboard</title>
</head>
<body>
<nav>
    <form action="{{ route('auth.logout') }}" method="POST">@csrf<button>Logout</button></form>
</nav>
<main>
    @yield('content')
</main>
</body>
</html>
