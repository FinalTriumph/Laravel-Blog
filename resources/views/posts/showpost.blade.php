@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="/posts" class="btn btn-default">All Posts</a>
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
                            <div class="pull-right">
                                <small>{{ $post->likes }} Likes</small>
                                <small>{{ $post->comments }} Comments</small>
                            </div>
                            @if(!Auth::guest() && Auth::user()->id === $post->user_id)
                                <hr/>
                                <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
                                {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                {!!Form::close()!!}
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