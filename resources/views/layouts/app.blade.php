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
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>


    <div class="site-container">
        <div class="site-pusher">

            <header class="header transparant">

                <a href="#" class="header__icon" id="header__icon"></a>
                <a href="#" class="header__logo">Booksharing</a>

                <nav class="menu">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @else
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                </nav>

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

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
