@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div>
                <div class="panel panel-body">
                    <img src="{{ $user->profile_picture }}" class="img-responsive" />
                    <br />
                    <p>{{ $user->name }}</p>
                    <p>Joined {{ $user->created_at }}</p>
                    @if(!Auth::guest() && Auth::user()->id === $user->id)
                        <a href="/user-profile/{{ $user->id }}/edit" class="btn btn-default">Edit Profile</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Your posts</div>
                <div class="panel-body">
                    <a href="posts/create" class="btn btn-default" id="create_new_post_btn">Create New Post</a>
                    <hr />
                    @if(count($posts))
                        @foreach($posts as $post)
                            <div class="well">
                                <div class=row>
                                <img src="{{ $post->cover_image }}" class="col-md-5 img-responsive "/>
                                <div class="col-md-5">
                                    <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                                    <small>Category: {{ $post->category }}</small><br />
                                    <small>Keywords: {{ $post->keywords }}</small><br />
                                    <br />
                                    <small>Written on {{ $post->created_at }}</small>
                                    <br />
                                    <small>{{ $post->likes }} Likes</small>
                                    <small>{{ $post->comments }} Comments</small>
                                </div>
                                <div class="pull-right col-md-2 text-center" >
                                    <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>
                                    <hr />
                                    {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST'])!!}
                                            {{Form::hidden('_method', 'DELETE')}}
                                            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                    {!!Form::close()!!}
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
    </div>
</div>
@endsection
