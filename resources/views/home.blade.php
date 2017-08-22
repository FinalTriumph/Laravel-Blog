@extends('layouts.app')

@section('title')
    Dashboard | Laravel Blog
@endsection

@section('content')
<div class="alert_div comment_delete_alert">
    <div class="alert alert-danger"></div>
</div>
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
    @if(!Auth::guest() && Auth::user()->id === $user->id)
        <a href="/user-profile/{{ $user->id }}/edit" class="btn btn-default" id="edit_profile_btn">Edit Profile</a>
    @endif
</div>
<div class="inline-l posts_div">
    <a href="posts/create" class="btn btn-default" id="create_new_post_btn">Create New Post</a>
    @if(count($posts))
        <div class="pull-right" style="margin: 0.3em 2em"><p>Total post count: {{ $post_count }}</p></div>
        @foreach($posts as $post)
            <div class="dashboard_post_div">
                <a href="/posts/{{ $post->id }}">
                    @if($post->cover_image == 'none')
                        <img src="http://i.imgur.com/P4yUVYl.jpg" class="img-responsive inline-db-i"/>
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
                            @if(explode(', ', $post->keywords)[count(explode(', ', $post->keywords)) - 1] == $keyword )
                                <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>
                            @else
                                <a href="/posts/keyword/{{ $keyword }}"><p1>{{$keyword}}</p1></a>,
                            @endif
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
                {!!Form::open(['id' => $post->id, 'action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'pull-right sp_del_form'])!!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::button('Delete', ['class' => 'btn btn-danger sp_edit_delete delete_post', 'data-id' => $post->id])}}
                {!!Form::close()!!}
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
<script type="text/javascript" >
/* global $ */
$(".delete_post").click(function() {
    var form_id = $(this).attr('data-id');
    $('.alert_div').show();
    $('.alert_div .alert').html("Are you sure you want to delete this post?<br /><button class='btn btn-danger del_com_conf_btn' onclick='submit(this)' data-id='"+form_id+"'>Yes</button><button class='btn del_com_conf_btn del_com_cancel'>Cancel</button>");
});

function submit(objBtn) {
    var form_id = objBtn.getAttribute('data-id');
    $('#' + form_id).submit();
}
</script>
@endsection
