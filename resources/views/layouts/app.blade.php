<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div id="header">
            <a href="/"><p class="pull-left logo_t">Laravel Blog</p></a>
            <div class="pull-right">
                <a href="/posts">Posts</a> | 
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <a href="{{ route('login') }}">Login</a> | 
                    <a href="{{ route('register') }}">Register</a>
                @else
                    <a href="/user-profile/{{ Auth::user()->id }}">My Posts</a> | 
                    <a href="/home">Dashboard</a> |
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" role="menu">
                            <a href="/posts/create" class="btn">Create New Post</a>
                            <a href="/user-profile/{{ Auth::user()->id }}/edit" class="btn">Edit Profile</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @include('inc.messages')
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ secure_asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
