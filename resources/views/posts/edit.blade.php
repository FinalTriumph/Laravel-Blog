@extends('layouts.app')

@section('title')
    Edit Post | Laravel Blog
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2 create_post_div">
        <p>Edit Post</p>
        <hr class="cr_n_p_hr"/>
        {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {{ Form::label('title', 'Post Title:') }}
                {{ Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title', 'required']) }}
            </div>
            <div class="form-group">
                {{ Form::label('body', 'Post Body:') }}
                {{ Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body', 'required', 'style' => 'resize: vertical']) }}
            </div>
            <div class="row">
                <div class="form-group col-xs-5">
                    {{ Form::label('category', 'Category: ') }}
                    {{ Form::select('category', [
                            'Business' => 'Business',
                            'Education' => 'Education',
                            'Entertainment' => 'Entertainment',
                            'Fashion' => 'Fashion',
                            'Finance' => 'Finance',
                            'Health' => 'Health',
                            'Lifestyle' => 'Lifestyle',
                            'Nature' => 'Nature',
                            'Relationships' => 'Relationships',
                            'Science' => 'Science',
                            'Sports' => 'Sports', 
                            'Technology' => 'Technology', 
                            'Travel' => 'Travel',
                            'Web Development' => 'Web Development',
                            'Other' => 'Other'
                        ], $post->category, ['placeholder' => 'Choose category ...', 'required']) }}
                </div>
                <div class="form-group col-xs-3">
                    @if($post->cover_image == 'none')
                        <img src="http://i.imgur.com/P4yUVYl.jpg" class="img-responsive pull-right edit_img"/>
                    @else
                        <img src="{{ $post->cover_image }}" class="img-responsive pull-right edit_img"/>
                    @endif
                </div>
                <div class="form-group col-xs-4">
                    {{ Form::label('post_image', 'Change post image:') }}
                    {{ Form::file('post_image') }}
                    <hr style="margin: 1em auto 0 auto"/>
                    <small>Maximum file size: 5MB</small>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('keywords', 'Keywords (optional):') }}
                {{ Form::text('keywords', $post->keywords, ['class' => 'form-control', 'placeholder' => 'keyword1, keyword2, keyword3, ...']) }}
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::submit('Update', ['class' => 'btn btn-primary submit_new_post_btn']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection