@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Post</div>

                <div class="panel-body">
                    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        <div class="form-group">
                            {{ Form::label('title', 'Post Title:') }}
                            {{ Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('body', 'Post Body:') }}
                            {{ Form::textarea('body', $post->body, ['class' => 'form-control', 'placeholder' => 'Body', 'style' => 'resize: vertical']) }}
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
                                        'Relationships' => 'Relationships',
                                        'Science' => 'Science',
                                        'Sports' => 'Sports', 
                                        'Technology' => 'Technology', 
                                        'Travel' => 'Travel',
                                        'Web Development' => 'Web Development',
                                        'Other' => 'Other'
                                    ], $post->category, ['placeholder' => 'Choose category ...']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('post_image', 'Post Image (optional, max 5MB):') }}
                                {{ Form::file('post_image') }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('keywords', 'Keywords (optional):') }}
                            {{ Form::text('keywords', $post->keywords, ['class' => 'form-control', 'placeholder' => 'keyword1, keyword2, keyword3, ...']) }}
                        </div>
                        {{ Form::hidden('_method', 'PUT') }}
                        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection