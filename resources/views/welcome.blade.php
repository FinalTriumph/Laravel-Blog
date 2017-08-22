<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Blog</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:200,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
        <style>
            html, body {
                color: #fff;
                font-family: 'Raleway', sans-serif;
                height: 100vh;
                margin: 0;
                text-align: center;
                background-image: url('http://i.imgur.com/6u1aQRO.jpg');
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            h1 {
                margin: 1em 1em 1em 0;
                font-weight: 200;
                text-align: right;
                font-size: 3em;
            }
            .content {
                height: 100vh;
                background-color: rgba(20, 20, 20, 0.3);
                word-wrap: break-word;
                overflow: auto;
                font-weight: 300;
            }
            .top-right {
                width: 100%;
                text-align: right;
                padding-top: 1em;
                margin-bottom: 5em;
            }
            .links {
                font-size: 0.9em;
                font-weight: 600;
                letter-spacing: .1rem;
                color: #eee;
                text-transform: uppercase;
                text-decoration: none;
            }
            .links a {
                padding: 0 2em;
                color: #eee;
            }
            .links a:hover {
                color: #fff;
                text-decoration: none;
            }
            .inline {
                display: inline-block;
                vertical-align: top;
            }
            .bg {
                width: 20%;
            }
            .desc {
                width: 70%;
                background-color: rgba(20, 20, 20, 0.7);
                padding: 2em 5em;
                margin-right: 0em;
                margin-bottom: 5em;
                padding-bottom: 1em;
                border: 1px solid rgba(0, 0, 0, 0.2);
                border-radius: 5px;
                -webkit-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
                box-shadow: 0px 1px 2px 0px rgba(0,0,0,0.75);
            }
            .desc a{
                color: #fff;
                text-decoration: underline;
            }
            .desc a:hover {
                color: #ddd;
            }
            .row {
                text-align: left;
            }
            .left_m {
                margin-left: 1em;
            }
            .b_t {
                font-weight: 400;
            }
        </style>
    </head>
    <body>
        <div class="content">
            @if (Route::has('login'))
            <div class="top-right links">
                <a href="/posts">Blog</a> |
                @auth
                    <a href="{{ url('/home') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a> |
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
            @endif
            <div class="inline lb">
                <h1>Laravel</br>Blog</h1>
            </div>
            <div class="inline desc">
                <p>This application was made as a part of Laravel/PHP/MySQL programming practice.</p>
                <hr />
                <div class="row">
                    <div class="col-xs-5">
                        <p class="b_t">All users can:</p>
                        <p class="left_m">- Register new account/login.</p>
                        <p class="left_m">- Browse through all posts.</p>
                        <p class="left_m">- View posts by category.</p>
                        <p class="left_m">- View posts by keyword.</p>
                        <p class="left_m">- Search posts</br>(returns results where search term is included in post keywords, title or body).</p>
                        <p class="left_m">- View single post.</p>
                        <p class="left_m">- View post comments.</p>
                        <p class="left_m">- View user profiles.</p>
                    </div>
                    <div class="col-xs-7">
                        <p class="b_t">Authenticated users can:</p>
                        <p class="left_m">- Create new posts with title, body, category, keywords, image.</p>
                        <p class="left_m">- Edit created posts.</p>
                        <p class="left_m">- Delete created posts.</p>
                        <p class="left_m">- Like/unlike posts.</p>
                        <p class="left_m">- Comment posts.</p>
                        <p class="left_m">- Delete added comments.</p>
                        <p class="left_m">- Delete all comments from created posts.</p>
                        <p class="left_m">- Edit profile (change name, about, email, password, profile picture).</p>
                        <p class="left_m">- Reset password using registered email.</p>
                        <p class="left_m">- Delete account.</p>
                    </div>
                </div>
                <hr />
                <p>There still may be some errors and if any spotted feel free to <a href="http://finaltriumph.tk/" target="_blank">contact me</a> and let me know.</p>
                <p>Made by <a href="http://finaltriumph.tk/" target="_blank">FinalTriumph</a>, 2017</p>
            </div>
        </div>
    </body>
</html>
