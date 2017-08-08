@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Single Post</div>

                <div class="panel-body">
                    @if($post)
                        <div class="well">
                            <img src="{{ $post->cover_image }}" class="img-responsive" />
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->body }}</p>
                            <br/>
                            <small>Written on {{ $post->created_at }} by {{$post->user->name}}</small>
                            <small class="like_btn pull-right" data-id="{{ $post->id }}">{{ $post->likes }} Likes</small>
                            @if(!Auth::guest() && Auth::user()->id === $post->user_id)
                                <hr/>
                                <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
                                {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                {!!Form::close()!!}
                            @endif
                            <hr id="c_line"/>
                            <p1> Comments ({{ $post->comments }})</p1>
                            @if(!Auth::guest())
                                <button class="btn btn-default pull-right add_comment_btn">Add Comment <span>(open)</span></button>
                                <br/>
                            @endif
                            <br/>
                            @if(!Auth::guest())
                                <div class="add_comment_form">
                                    {!! Form::open(['action' => ['PostsController@addComment', $post->id], 'method' => 'POST']) !!}
                                        {{ Form::textarea('comment', '', ['class' => 'form-control', 'placeholder' => 'Comment', 'style' => 'resize: vertical', 'rows' => '5', 'required']) }}
                                        <br />
                                        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
                                        <hr/>
                                    {!! Form::close() !!}
                                </div>
                            @endif
                            <br/>
                            @if(count($comments))
                                @foreach($comments as $comment)
                                    <div class="comment_div">
                                        <p>{{ $comment->user->name }} | {{ $comment->created_at }}</p>
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                @endforeach
                                {{ $comments->links() }}
                            @else
                                <p>This post don't have comments yet</p>
                            @endif
                        </div>
                    @else
                        <p>Post not found</p>
                        <p>Not existing post id</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/like.js') }}"></script>
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
            }, 500);
            $(".add_comment_btn span").html('(close)');
        } else {
            $(".add_comment_btn span").html('(open)');
        }
        $(".add_comment_form").slideToggle(500);
    });
    
</script>
@endsection