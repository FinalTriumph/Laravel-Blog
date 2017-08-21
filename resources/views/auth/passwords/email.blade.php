@extends('layouts.app')

@section('title')
    Reset Password | Laravel Blog
@endsection

@section('content')
@if (session('status'))
    <div class="alert_div">
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-8 col-md-offset-2 log_reg_div">
        <p class="log_reg_p">Reset Password</p>
        <hr class="log_reg_hr"/>
        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary log_reg_btn reg_btn">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
/* global $ */
    $('.alert_div').click(function() {
        $('.alert_div').hide();
    });
</script>
@endsection
