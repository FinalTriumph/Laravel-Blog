@extends('layouts.app')

@section('title')
    {{ $user->name }} | Laravel Blog
@endsection

@section('content')
<div class="inline-m profile_info_div">
    <img src="{{ $user->profile_picture }}" class="img-responsive" />
    <br />
    <strong>{{ $user->name }}</strong>
    <hr class="prof_hr"/>
    @if($user->about)
        <p>{{ $user->about }}</p>
        <hr class="prof_hr"/>
    @endif
    <small>Joined {{ date('F d, Y', strtotime($user->created_at)) }}</small>
</div>
<div class="inline-l posts_div">
    @if(count($posts))
    <p>Total post count: {{ $post_count }}</p>
    <hr class="cat_hr"/>
        @foreach($posts as $post)
            <div class="post_div_background_image">
                <a href="/posts/{{ $post->id }}">
                    @if($post->cover_image == 'none')
                        <img src="http://i.imgur.com/P4yUVYl.jpg" class="back_img"/>
                    @else
                        <img src="{{ $post->cover_image }}" class="back_img"/>
                    @endif
                </a>
                <div class="post_div_on_image">
                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                    <!--<p>{{ str_limit($post->body, $limit = 150, $end = '...') }}</p>-->
                    <p>{{ \Illuminate\Support\Str::words(strip_tags($post->body), 30, ' ...') }}</p>
                    <small>Category: <a href="/posts/category/{{ $post->category }}">{{ $post->category }}</a></small>
                    @if ($post->keywords != "")
                        <small class="pull-right">
                        @foreach(explode(', ', $post->keywords) as $keyword)
                            @if(explode(', ', $post->keywords)[count(explode(', ', $post->keywords)) - 1] == $keyword )
                            <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>
                            @else
                            <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>,
                            @endif
                        @endforeach
                        </small>
                    @endif
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
        <div style="margin-top: 1em"><p>No posts to show</p></div>
    @endif

</div>
@endsection

@section('scripts')
<script src="{{ secure_asset('js/like.js') }}"></script>
<script src="{{ secure_asset('js/change_img_size.js') }}"></script>
@endsection