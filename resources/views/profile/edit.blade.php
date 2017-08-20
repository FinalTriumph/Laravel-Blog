@extends('layouts.app')

@section('title')
    Edit Profile | Laravel Blog
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2 create_post_div">
        <p>Edit Profile</p>
        <hr class="cr_n_p_hr"/>
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
                {{ Form::label('about', 'About:') }}
                {{ Form::textarea('about', $user->about, ['class' => 'form-control', 'placeholder' => 'About', 'rows' => '3', 'style' => 'resize: vertical']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email:') }}
                {{ Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => 'Email']) }}
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::submit('Update', ['class' => 'btn btn-primary submit_new_post_btn']) }}
        {!! Form::close() !!}
        <hr id="cp_line"/>
        <button class="btn btn-default change_password_btn">Change Password <span><img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close"></span></button>
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
            {{ Form::submit('Submit', ['class' => 'btn btn-primary submit_new_post_btn']) }}
        {!! Form::close() !!}
        <hr id="da_line"/>
        <button class="btn btn-default delete_account_btn">Delete Account <span><img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close"></span></button>
        <br/><br />
        {!!Form::open(['id' => 'delete_account_form', 'action' => ['ProfileController@destroy', $user->id], 'method' => 'POST'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            <div class="form-group">
                {{ Form::label('profile_password', 'Password:') }}
                {{ Form::password('profile_password', ['class' => 'form-control']) }}
            </div>
            {{Form::submit('Delete Account', ['class' => 'btn btn-danger del_acc_submit'])}}
        {!!Form::close()!!}
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
/* global $ */
    $(".change_password_btn").click(function() {
        if ($("#change_password_form").is(":hidden")) {
            $('html, body').animate({
                scrollTop: $('#cp_line').offset().top
            }, 500, function() {
                $(".change_password_btn span").html('<img src="http://i.imgur.com/D6TqF0Z.png" class="ac_open_close">');
            });
        } else {
            setTimeout(function() {
                $(".change_password_btn span").html('<img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close">');
            }, 500);
        }
        $("#change_password_form").slideToggle(500);
    });
    
    $(".delete_account_btn").click(function() {
        if ($("#delete_account_form").is(":hidden")) {
            $('html, body').animate({
                scrollTop: $('#da_line').offset().top
            }, 500, function() {
                $(".delete_account_btn span").html('<img src="http://i.imgur.com/D6TqF0Z.png" class="ac_open_close">');
            });
        } else {
            setTimeout(function() {
                $(".delete_account_btn span").html('<img src="http://i.imgur.com/EpAaK4G.png" class="ac_open_close">');
            }, 500);
        }
        $("#delete_account_form").slideToggle(500);
    });
</script>
@endsection