
@extends('layouts.app')
@section('style')
    .btn-blocks{
    width:100% !important;
    }
    @endsection
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <img src="{{URL::asset('public/images/hungerByeMain.png')}}" width="250">
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if (session('error'))
                <div  class="callout callout-danger">
                    <h5>{{ session('error') }}</h5>
                </div>
            @endif
            <form action="{{route('loginCheck')}}" method="post">
                @csrf
                <div class="form-group has-feedback">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="form-group has-feedback">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>

                    </div>
                    <!-- /.col -->

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-default btn-flat btn-blocks" href="{{ url('auth/google') }}" role="button" style="text-transform:none">
                            <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                            Login with Google
                        </a>
                        <a href="{{route('register')}}" class="pull-right">Register Here!</a>
                    </div>

                </div>
            </form>


        </div>
        <!-- /.login-box-body -->
    </div>

@endsection

