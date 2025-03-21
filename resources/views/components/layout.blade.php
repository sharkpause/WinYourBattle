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
            <div class="navbar-collapse" id="navbar-supported-content">
                <ul class="navbar-nav ml-auto gap-3">
                  <li class="nav-item active">
                    <a class="nav-link button-click-animation
                              {{ request()->routeIs('posts.index') ? 'text-primary' : 'text-white' }}" href="{{ route('posts.index') }}">
                        <i class="fa-solid fa-house me-1"></i>
                        Latest Posts
                    </a>
                  </li>
                  @auth
                  <li class="nav-item">
                    <a class="nav-link button-click-animation
                              {{ request()->routeIs('posts.following') ? 'text-primary' : 'text-white' }}" href="{{ route('posts.following') }}">
                        <i class="fa-solid fa-user-group me-1"></i>
                        Followed posts
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link button-click-animation
                              {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-white' }}" href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-chart-line me-1"></i>    
                        Dashboard
                    </a>
                  </li>
                  @endauth
                </ul>
            </div>

            <ul style="list-style-type: none" class="navbar-nav py-2">
                @auth
                <li class="nav-item dropdown me-4">
                    <a href="#" type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell text-white mt-2_5 font-size-150-percent"></i>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                         <p class="dropdown-header">Notifications</p>
                         <a class="dropdown-item pointer-on-hover" id="follow-requests-button" data-url="{{ route('users.follow-requests', Auth::id()) }}">Follow requests</a>
                    </div>
                    </li>

                <li class="nav-item dropdown">
                <a href="#" type="button" id="navbar-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->image }}" class="rounded-circle hw-45px button-click-animation">
                </a>
                
                <div class="dropdown-menu dropdown-menu-end shadow border-radius-1-rem" aria-labelledby="navbar-dropdown">
                     <p class="dropdown-header">{{ Auth::user()->username }}</p> 
                     <a class="dropdown-item" href="{{ route('profile', Auth::id()) }}">Profile</a>
                     <form action="{{ route('logout') }}" method="POST">
                         @csrf
                         <button class="dropdown-item">Log out</button>
                     </form>
                </div>
                </li>
                @endauth
                @guest
                    <a class="nav-link text-white button-click-animation
                              {{ request()->routeIs('login') ? 'text-primary' : '' }}" href="{{ route('login') }}">Log in</a>
                    <a class="nav-link text-white button-click-animation
                            {{ request()->routeIs('register') ? 'text-primary' : '' }}" href="{{ route('register') }}">Register</a>
                @endguest
            </ul>
        </nav>
    </header>

    <main class="container-fluid px-5">
        {{ $slot }}
    </main>

</body>
</html>

@vite('resources/js/layout.js')