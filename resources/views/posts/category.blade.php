@extends('layouts.app')

@section('title')
    {{ $activeCategory }} | Laravel Blog
@endsection

@section('content')
<div class="inline-s category_side_div">
    <a href="/posts" class="btn category_side_btn">All ({{ $total }})</a>
    @foreach($categories as $category)
        @if($category->title != "Other")
            @if($category->title == $activeCategory)
            <a href="/posts/category/{{ $category->title }}" class="btn category_side_btn active_side_category">{{ $category->title }} ({{ $category->count }})</a>
            @else
            <a href="/posts/category/{{ $category->title }}" class="btn category_side_btn">{{ $category->title }} ({{ $category->count }})</a>
            @endif
        @endif
    @endforeach
    @if($categories[8]['title'] == $activeCategory)
        <a href="/posts/category/Other" class="btn category_side_btn active_side_category">Other ({{ $categories[8]['count'] }})</a>
    @else
        <a href="/posts/category/Other" class="btn category_side_btn">Other ({{ $categories[8]['count'] }})</a>
    @endif
</div>
<div class="inline-l posts_div posts_div_w_m">
    <p>{{ $activeCategory }}</p>
    <hr class="cat_hr"/>
    @if(count($posts))
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
        <p>Nothing to show</p>
    @endif
</div>
<div class="inline-s keywords_div">
    <p>Popular Keywords</p>
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
<script src="{{ secure_asset('js/change_img_size.js') }}"></script>
@endsection