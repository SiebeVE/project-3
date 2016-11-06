<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/libs.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</head>

<body>


    <div class="site-container">
        <div class="site-pusher">

            <header class="header transparant">

                <a href="#" class="header__icon" id="header__icon"></a>

                <div class="header__left">
                    @yield('header_left')
                </div>


                <nav class="menu pull-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @else
                        <a href="{{route('library')}}">Library</a>
                        <a href="{{route('user.view')}}">My profile</a>
                        <a href="{{route('book.index')}}">My books</a>
                        <a href="{{ url('/logout') }}" data-method="post" data-csrf="{{csrf_token()}}">
                            Logout
                        </a>
                    @endif
                </nav>


                <div class="pull-right">
                    @yield('header_right')
                </div>

            </header>

            <div class="site-content">
                @yield('content')
            </div>
            <!-- END site-content -->
            <div class="site-cache" id="site-cache"></div>
        </div>
        <!-- END site-pusher -->
    </div>
    <!-- END site-container -->
    <script src="//cdn.jsdelivr.net/medium-editor/latest/js/medium-editor.min.js"></script>
</body>
</html>
