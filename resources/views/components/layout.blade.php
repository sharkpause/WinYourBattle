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
<body class="bg-gray">
    <header class="mb-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-dracula px-5 fixed-top">
            <div class="navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ route('posts.index') }}">Home</a>
                  </li>
                </ul>
            </div>

            <ul style="list-style-type: none" class="navbar-nav py-2">
                @auth
                <li class="nav-item dropdown">
                <a href="#" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" class="rounded-circle hw-45px">
                </a>
                
                <div class="dropdown-menu dropdown-menu-end shadow border-radius-1-rem" aria-labelledby="navbarDropdown">
                     <p class="dropdown-header">{{ auth()->user()->username }}</p> 
                     <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                     <form action="{{ route('logout') }}" method="POST">
                         @csrf
                         <button class="dropdown-item">Log out</button>
                     </form>
                </div>
                </li>
                @endauth
                @guest
                    <a class="nav-link text-white" href="{{ route('login') }}">Log in</a>
                    <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                @endguest
            </ul>
        </nav>
    </header>

    <main class="container-fluid px-5">
        {{ $slot }}
    </main>

</body>
</html>