@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Profile</div>

                <div class="panel-body">
                    {!! Form::open(['action' => ['ProfileController@update', $user->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <img src="{{ $user->profile_picture }}" class="img-responsive" />
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('profile_picture', 'Profile picture (max 2MB):') }}
                                {{ Form::file('profile_picture') }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('name', 'Name:') }}
                            {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'Name']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email:') }}
                            {{ Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email']) }}
                        </div>
                        {{ Form::hidden('_method', 'PUT') }}
                        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                    <hr id="c_line"/>
                    <button class="btn btn-default change_password_btn">Change Password <span>(open)</span></button>
                    <br/><br />
                    {!! Form::open([ 'id'=>'change_password_form', 'action' => ['ProfileController@changePassword', $user->id], 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{ Form::label('password', 'Current Password:') }}
                            {{ Form::password('password', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('new-password', 'New Password:') }}
                            {{ Form::password('new-password', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('confirm-new-password', 'Confirm New Password:') }}
                            {{ Form::password('confirm-new-password', ['class' => 'form-control']) }}
                        </div>
                        {{ Form::hidden('_method', 'PUT') }}
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
/* global $ */
    $(".change_password_btn").click(function() {
        if ($("#change_password_form").is(":hidden")) {
            $('html, body').animate({
                scrollTop: $('#c_line').offset().top
            }, 500);
            $(".change_password_btn span").html('(close)');
        } else {
            $(".change_password_btn span").html('(open)');
        }
        $("#change_password_form").slideToggle(500);
    });
</script>
@endsection