@extends('layouts.app')

@section('title')
    Dashboard | Laravel Blog
@endsection

@section('content')
<div class="inline-m profile_info_div">
    <img src="{{ $user->profile_picture }}" class="img-responsive" />
    <br />
    <p>{{ $user->name }}</p>
    <p>Joined {{ date('F d, Y', strtotime($user->created_at)) }}</p>
    @if(!Auth::guest() && Auth::user()->id === $user->id)
        <a href="/user-profile/{{ $user->id }}/edit" class="btn btn-default">Edit Profile</a>
    @endif
</div>
<div class="inline-l posts_div">
    <a href="posts/create" class="btn btn-default" id="create_new_post_btn">Create New Post</a>
    @if(count($posts))
        @foreach($posts as $post)
            <div class="dashboard_post_div">
                <a href="/posts/{{ $post->id }}">
                    @if($post->cover_image == 'none')
                        <img src="http://i.imgur.com/RkjJiWE.jpg" class="img-responsive inline-db-i"/>
                    @else
                        <img src="{{ $post->cover_image }}" class="img-responsive inline-db-i"/>
                    @endif
                </a>
                <div class="inline-db">
                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                    <small>Category: <a href="/posts/category/{{ $post->category }}">{{ $post->category }}</a></small><br />
                    @if ($post->keywords != "")
                        <small>Keywords: 
                        @foreach(explode(', ', $post->keywords) as $keyword)
                            <a href="/posts/keyword/{{ $keyword }}">{{$keyword}}</a>, 
                        @endforeach
                        </small><br />
                    @endif
                    <small>{{ date('F d, Y', strtotime($post->created_at)) }}</small><br />
                    @if(!Auth::guest() && count($likes))
                        @if(in_array($post->id, $likes))
                            <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/pSghtg6.png' class="heart_icon"/></p1>
                        @else
                            <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>
                        @endif
                    @else
                        <p1 class="like_btn" data-id="{{ $post->id }}">{{ $post->likes }} <img src='http://i.imgur.com/5098TmX.png' class="heart_icon"/></p1>    
                    @endif
                        <a href="/posts/{{ $post->id }}#comments" class="comment_link_i"><p1>{{ $post->comments }} <img src="http://i.imgur.com/WpaQR1B.png" class="comment_icon"/></p1></a>
                </div>
                <a href="/posts/{{$post->id}}/edit" class="btn btn-default sp_edit_delete">Edit</a>
                {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right sp_del_form'])!!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete', ['class' => 'btn btn-danger sp_edit_delete'])}}
                {!!Form::close()!!}
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
