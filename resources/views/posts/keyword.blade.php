@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="category_side_btn">
                <a href="/posts"><p>All ({{ $total }})</p></a>
            </div>
            @foreach($categories as $category)
                <div class="category_side_btn">
                    <a href="/posts/category/{{ $category->title }}"><p>{{ $category->title }} ({{ $category->count }})</p></a>
                </div>
            @endforeach
        </div>
        <div class="col-md-8">
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
                                        <small class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} Likes</small>
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
        <div class="col-md-2">
            <p>Popular Keywords</p>
            <hr />
            @foreach($keywords as $keyword)
                <div>
                    <a href="/posts/keyword/{{ $keyword->title }}"><p>{{ $keyword->title }} ({{ $keyword->count }})</p></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ secure_asset('js/like.js') }}"></script>
@endsection