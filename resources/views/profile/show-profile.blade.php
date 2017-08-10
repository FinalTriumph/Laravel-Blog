@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-body">
                <img src="{{ $user->profile_picture }}" class="img-responsive" />
                <br />
                <p>{{ $user->name }}</p>
                <p>Joined {{ $user->created_at }}</p>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Posts</div>

                <div class="panel-body">
                    @if(count($posts))
                        @foreach($posts as $post)
                            <div class="well post_div_background_image">
                                <img src="{{ $post->cover_image }}" class="img-responsive" />
                                <div class="post_div_on_image">
                                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                                    <p>{{ str_limit($post->body, $limit = 150, $end = '...') }}</p>
                                    <small>Category: {{ $post->category }}</small>
                                    <small class="pull-right">Keywords: {{ $post->keywords }}</small>
                                    <hr />
                                    <small>Written on {{ $post->created_at }} by <a href="/user-profile/{{ $post->user->id }}">{{$post->user->name}}</a></small>
                                    <div class="pull-right">
                                        @if(!Auth::guest() && count($likes))
                                            @if(in_array($post->id, $likes))
                                                <small class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/pSghtg6.png' class="heart_icon"/></small>
                                            @else
                                                <small class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></small>
                                            @endif
                                        @else
                                            <small class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></small>    
                                        @endif
                                        <a href="/posts/{{ $post->id }}#comments"><small>{{ $post->comments }} Comments</small></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p>No posts found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ secure_asset('js/like.js') }}"></script>
@endsection