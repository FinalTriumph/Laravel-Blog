@extends('layouts.app')

@section('title')
    {{ $user->name }} | Laravel Blog
@endsection

@section('content')
<div class="inline-m profile_info_div">
    <img src="{{ $user->profile_picture }}" class="img-responsive" />
    <br />
    <p>{{ $user->name }}</p>
    <p>Joined {{ date('F d, Y', strtotime($user->created_at)) }}</p>
</div>
<div class="inline-l posts_div">
    @if(count($posts))
        @foreach($posts as $post)
            <div class="post_div_background_image">
                <img src="{{ $post->cover_image }}" class="img-responsive" />
                <div class="post_div_on_image">
                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                    <p>{{ str_limit($post->body, $limit = 150, $end = '...') }}</p>
                    <small>Category: {{ $post->category }}</small>
                    <small class="pull-right">Keywords: {{ $post->keywords }}</small>
                    <hr />
                    <small>{{ date('F d, Y', strtotime($post->created_at)) }}</small>
                    <div class="pull-right likes_comments">
                    @if(!Auth::guest() && count($likes))
                        @if(in_array($post->id, $likes))
                            <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/pSghtg6.png' class="heart_icon"/></p1>
                        @else
                            <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>
                        @endif
                    @else
                        <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>    
                    @endif
                        <a href="/posts/{{ $post->id }}#comments"><p1>{{ $post->comments }} <img src="http://i.imgur.com/bXww3RY.png" class="comment_icon"/></p1></a>
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
@endsection

@section('scripts')
<script src="{{ secure_asset('js/like.js') }}"></script>
@endsection