<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Twitter Clone</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" >

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}"><i class="fa-brands fa-twitter me-2 twitter-color"></i>Twitter Clone</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @guest
                @yield('content') 
            @else
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <h3 class="mb-4"><a href="/home" class="text-decoration-none text-dark p-2 rounded-pill link-hover {{request()->route()->getName() == 'home' ? 'fw-bold' : false}}"><i class="fa-solid fa-house me-2 {{request()->route()->getName() == 'home' ? 'twitter-color' : false}}"></i>Home</a></h3>
                        <h3 class="mb-4"><a href="" class="text-decoration-none text-dark p-2 rounded-pill link-hover"><i class="fa-solid fa-envelope me-2"></i>Messages</a></h3>
                        <h3 class="mb-4"><a href="{{ route('profile', Auth::user()->username) }}" class="text-decoration-none text-dark p-2 rounded-pill link-hover {{request()->route()->getName() == 'profile' ? 'fw-bold' : false}}"><i class="fa-solid fa-user me-2 {{request()->route()->getName() == 'profile' ? 'twitter-color' : false}}"></i>Profile</a></h3>
                        <h3 class="mb-4"><a class="text-decoration-none text-dark p-2 rounded-pill link-hover" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>{{ __('Logout') }}</a></h3>
                    </div>
                    <div class="col-5 p-0 start-section border border-light">
                        @yield('content') 
                    </div>
                    <div class="col-3">
                        <form action="" method="GET">
                            <div class="input-group mb-3">
                                <input  type="text" class="form-control border-info rounded-pill" placeholder="Search..." aria-describedby="button-addon">
                                <button class="btn btn-outline-secondary d-none" type="button" id="button-addon">Button</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endguest
        </main>
    </div>
</body>
</html>
