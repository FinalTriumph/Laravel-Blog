@extends('layouts.app')

@section('title')
    All Posts | Laravel Blog
@endsection

@section('content')
<div class="inline-s">
    <a href="/posts" class="btn category_side_btn active_side_category">All ({{ $total }})</a>
    @foreach($categories as $category)
        <a href="/posts/category/{{ $category->title }}" class="btn category_side_btn">{{ $category->title }} ({{ $category->count }})</a>
    @endforeach
</div>
<div class="inline-l posts_div">
    @if(count($posts))
        @foreach($posts as $post)
            <div class="post_div_background_image">
                <a href="/posts/{{ $post->id }}">
                    @if($post->cover_image == 'none')
                    <img src="http://i.imgur.com/RkjJiWE.jpg" class="img-responsive"/>
                    @else
                        <img src="{{ $post->cover_image }}" class="img-responsive"/>
                @endif
                </a>
                <div class="post_div_on_image">
                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                    <p>{{ str_limit($post->body, $limit = 150, $end = '...') }}</p>
                    <small>Category: <a href="/posts/category/{{ $post->category }}">{{ $post->category }}</a></small>
                    @if ($post->keywords != "")
                        <small class="pull-right">
                        @foreach(explode(', ', $post->keywords) as $keyword)
                            <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>, 
                        @endforeach
                        </small>
                    @endif
                    <hr />
                    <img src="{{ $post->user->profile_picture }}" class="small_prof_pic inline"/>
                    <div class="inline">
                        <a href="/user-profile/{{ $post->user->id }}"><p1>{{$post->user->name}}</p1></a><br />
                        <small>{{ date('F d, Y', strtotime($post->created_at)) }}</small>
                    </div>
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
<div class="inline-s keywords_div">
    <p>{{ count($keywords) }} Most Popular Keywords</p>
    <hr/>
    @foreach($keywords as $keyword)
        @if($loop->iteration < 11)
            <a href="/posts/keyword/{{ $keyword->title }}" class="keyword k_first">{{ $keyword->title }} </a>
        @elseif($loop->iteration < 21)
            <a href="/posts/keyword/{{ $keyword->title }}" class="keyword k_second">{{ $keyword->title }} </a>
        @else
            <a href="/posts/keyword/{{ $keyword->title }}" class="keyword k_third">{{ $keyword->title }} </a>
        @endif
    @endforeach
</div>
@endsection

@section('scripts')
<script src="{{ secure_asset('js/like.js') }}"></script>
@endsection