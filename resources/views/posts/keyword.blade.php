@extends('layouts.app')

@section('content')
<div class="inline-s">
    <a href="/posts" class="btn category_side_btn">All ({{ $total }})</a>
    @foreach($categories as $category)
        <a href="/posts/category/{{ $category->title }}" class="btn category_side_btn">{{ $category->title }} ({{ $category->count }})</a>
    @endforeach
</div>
<div class="inline-l">
    <div class="panel panel-default">
        <div class="panel-heading">Posts with keyword - '{{ $activeKeyword }}'</div>

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