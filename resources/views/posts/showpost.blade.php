@extends('layouts.app')
@section('title')
    {{ $post->title }} | Laravel Blog
@endsection

@section('content')
<div class="inline-s">
    <a href="/posts" class="btn category_side_btn">All ({{ $total }})</a>
    @foreach($categories as $category)
        <a href="/posts/category/{{ $category->title }}" class="btn category_side_btn">{{ $category->title }} ({{ $category->count }})</a>
    @endforeach
</div>
<div class="inline-l posts_div single_post_div">
    @if($post)
        @if(!Auth::guest() && Auth::user()->id === $post->user_id)
            <a href="/posts/{{$post->id}}/edit" class="btn btn-default sp_edit_delete">Edit</a>
            {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right sp_del_form'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger sp_edit_delete'])}}
            {!!Form::close()!!}
        @endif
        @if($post->cover_image == 'none')
            <img src="http://i.imgur.com/RkjJiWE.jpg" class="img-responsive single_post_img"/>
        @else
            <img src="{{ $post->cover_image }}" class="img-responsive single_post_img"/>
        @endif
        <h3>{{ $post->title }}</h3>
        <small>Category: <a href="/posts/category/{{ $post->category }}">{{ $post->category }}</a></small>
        @if ($post->keywords != "")
            <small class="pull-right">
                @foreach(explode(', ', $post->keywords) as $keyword)
                    <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>, 
                @endforeach
            </small>
        @endif
        <p>{{ $post->body }}</p>
        <img src="{{ $post->user->profile_picture }}" class="small_prof_pic inline"/>
        <div class="inline">
            <a href="/user-profile/{{ $post->user->id }}"><p1>{{$post->user->name}}</p1></a><br />
            <small class="time">{{ date('F d, Y', strtotime($post->created_at)) }}</small>
        </div>
        @if(!Auth::guest() && count($likes))
            @if(in_array($post->id, $likes))
                <p1 class="like_btn pull-right" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/pSghtg6.png' class="heart_icon"/></p1>
            @else
                <p1 class="like_btn pull-right" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>
            @endif
        @else
            <p1 class="like_btn pull-right" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>    
        @endif
        <hr id="c_line"/>
        <p1> Comments ({{ $post->comments }})</p1>
        @if(!Auth::guest())
            <button class="btn btn-default pull-right add_comment_btn">Add Comment <span><img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close"></span></button>
        @endif
        <br /><br />
        @if(!Auth::guest())
            <div class="add_comment_form">
                {!! Form::open(['action' => ['PostsController@addComment', $post->id], 'method' => 'POST']) !!}
                    {{ Form::textarea('comment', '', ['class' => 'form-control', 'placeholder' => 'Comment', 'style' => 'resize: vertical', 'rows' => '5', 'required']) }}
                    {{Form::submit('Submit', ['class' => 'btn btn-primary submit_comment_btn'])}}
                {!! Form::close() !!}
            </div>
        @endif
        @if(count($comments))
            @foreach($comments as $comment)
                <div class="comment_div">
                    <img src="{{ $comment->user->profile_picture }}" class="small_prof_pic inline"/>
                    <div class="inline">
                        <a href="/user-profile/{{ $comment->user->id }}"><p1>{{$comment->user->name}}</p1></a><br />
                        <small>{{ date('F d, Y', strtotime($post->created_at)) }}</small>
                    </div>
                    <br />
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
            <div class="text-center">
                {{ $comments->links() }}
            </div>
        @else
            <p>This post don't have comments</p>
        @endif
    @else
        <p>Post not found</p>
        <p>Not existing post id</p>
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
<script type="text/javascript">
/* global $ */
$(document).ready(function() {
    if (window.location.href.indexOf("?page=") > -1) {
        $(window).scrollTop($('#c_line').offset().top);
    } else if (window.location.hash === '#comments') {
        $('html, body').animate({
            scrollTop: $('#c_line').offset().top
        }, 1000);
    }
});

$(".add_comment_btn").click(function() {
        if ($(".add_comment_form").is(":hidden")) {
            $('html, body').animate({
                scrollTop: $('#c_line').offset().top
            }, 500, function() {
                $(".add_comment_btn span").html('<img src="http://i.imgur.com/D6TqF0Z.png" class="ac_open_close">');
            });
            //$(".add_comment_btn span").html('<img src="http://i.imgur.com/D6TqF0Z.png" class="ac_open_close">');
        } else {
            setTimeout(function() {
                $(".add_comment_btn span").html('<img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close">');
            }, 500);
            //$(".add_comment_btn span").html('<img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close">');
        }
        $(".add_comment_form").slideToggle(500);
    });
    
</script>
@endsection