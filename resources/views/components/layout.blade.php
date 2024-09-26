<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="mb-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-dark px-5">
            <div class="navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ route('home') }}">Home</a>
                  </li>
                </ul>
            </div>

            <ul style="list-style-type: none">
              <li class="nav-item dropdown pt-3">
                <a href="#" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://picsum.photos/30" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end sharp-corners" aria-labelledby="navbarDropdown">
                    @auth
                        <p class="dropdown-header">{{ auth()->user()->username }}</p> 

                        <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button class="dropdown-item">Log out</button>
                        </form>
                    @endauth
                    @guest
                        <p class="dropdown-header">Guest</p> 

                        <a class="dropdown-item" href="{{ route('login') }}">Log in</a>
                        <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                    @endguest
                </div>
              </li>
            </ul>
        </nav>
    </header>

    <main class="container-fluid px-5">
        {{ $slot }}
    </main>

</body>
</html>