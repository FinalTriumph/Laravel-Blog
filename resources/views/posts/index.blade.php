@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">All Posts</div>

                <div class="panel-body">
                    @if(count($posts))
                        @foreach($posts as $post)
                            <div class="well post_div_background_image">
                                <img src="{{ $post->cover_image }}" class="img-responsive" />
                                <div class="post_div_on_image">
                                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                                    <p>{{ str_limit($post->body, $limit = 150, $end = '...') }}</p>
                                    <hr />
                                    <small>Written on {{ $post->created_at }} by {{$post->user->name}}</small>
                                    <div class="pull-right">
                                        <small>{{ $post->likes }} Likes</small>
                                        <small>{{ $post->comments }} Comments</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $posts->links() }}
                    @else
                        <p>No posts found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection