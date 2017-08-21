@extends('layouts.app')

@section('title')
    Create New Post | Laravel Blog
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2 create_post_div">
        <p>Create New Post</p>
        <hr class="cr_n_p_hr"/>
        {!! Form::open(['action' => 'PostsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {{ Form::label('title', 'Post Title:') }}
                {{ Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title', 'required']) }}
            </div>
            <div class="form-group">
                {{ Form::label('body', 'Post Body:') }}
                {{ Form::textarea('body', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body', 'required', 'style' => 'resize: vertical']) }}
            </div>
            <div class="row">
                <div class="form-group col-md-6">
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
                        ], null, ['placeholder' => 'Choose category ...', 'required']) }}
                </div>
                <div class="form-group col-md-6">
                    {{ Form::label('post_image', 'Post image:') }}
                    {{ Form::file('post_image') }}
                    <hr style="margin: 1em auto 0 auto"/>
                    <small>Maximum file size: 5MB</small>
                    <hr style="margin: 0"/>
                    <small>If not selected, default image will be added.</small>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('keywords', 'Keywords (optional):') }}
                {{ Form::text('keywords', '', ['class' => 'form-control', 'placeholder' => 'keyword1, keyword2, keyword3, ...']) }}
            </div>
            {{ Form::submit('Submit', ['class' => 'btn btn-primary submit_new_post_btn']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection