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
            <div id="search_div">
                <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Search ..."/><button id="submit_search" class="btn btn-default"><img src="http://i.imgur.com/VVVVBvq.png" id="search_icon" /></button>
            </div>
            <div class="pull-right">
                <a href="/posts">Posts</a> | 
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <a href="{{ route('login') }}">Login</a> | 
                    <a href="{{ route('register') }}">Register</a>
                @else
                    <a href="/home">Dashboard</a> |
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{Auth::user()->profile_picture}}" class="header_prof_img">
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
        <div id="content_div">
            @yield('content')
        </div>
        <div id="footer" class="row">
            <div class="col-xs-2 footer_lb">
                <h3>Laravel<br/>Blog</h3>
            </div>
            <div class="col-xs-8 footer_cont">
                <p>Laravel / PHP / MySQL practice project</p>
                <p>2017 <span class="sep_line">|</span> Made by <a href="http://finaltriumph.tk/" target="_blank">FinalTriumph</a></p>
            </div>
            <div class="col-xs-2">
                <img src="http://i.imgur.com/8ntI3rY.png" id="arrow-img"/>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ secure_asset('js/app.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        /*global CKEDITOR $*/
        $(".dropdown").on("show.bs.dropdown", function(event){
            $("#header").css("opacity", "1");
            $("#header").hover(function() {
                $("#header").css("opacity", "1");
            }, function() {
                $("#header").css("opacity", "1");
            });
        });
        
        $(".dropdown").on("hide.bs.dropdown", function(event){
            $("#header").css("opacity", "0.9");
            $("#header").hover(function() {
                $("#header").css("opacity", "1");
            }, function() {
                $("#header").css("opacity", "0.9");
            });
        });
        
        $("#arrow-img").click(function() {
            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 500);
        });
        
        $("#submit_search").click(function() {
            var term = $("input[name='search_term']").val();
            if (!term.replace(/\s/g, '').length) {
                $("input[name='search_term']").val("Type your search term here");
            } else {
                window.location = window.location.origin + "/posts/search/" + term;
            }
        });
        
        $("input[name='search_term']").keypress(function(e) {
            if (e.which === 13) {
                $("#submit_search").click();
            }
        });
        
        $("input[name='search_term']").focus(function() {
            $("#header").css("opacity", "1");
            $("#header").hover(function() {
                $("#header").css("opacity", "1");
            }, function() {
                $("#header").css("opacity", "1");
            });
        });
        $("input[name='search_term']").blur(function() {
            $("#header").css("opacity", "0.9");
            $("#header").hover(function() {
                $("#header").css("opacity", "1");
            }, function() {
                $("#header").css("opacity", "0.9");
            });
        });
        
        
        
        CKEDITOR.replace( 'article-ckeditor' );
    </script>
    @yield('scripts')
</body>
</html>
