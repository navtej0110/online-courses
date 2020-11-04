@extends('layouts.admin.login')

@section('content')
<div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1 box-shadow-2 p-0">
    <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
        <div class="card-header no-border pb-0">
            <div class="card-title text-xs-center">
                <!--<img src="../../app-assets/images/logo/robust-logo-dark.png" alt="branding logo">-->
                <h2><a href="{{route('home-page')}}">mangostudio.tech</a></h2>
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>We will send you a link to reset your password.</span></h6>
        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                @if (session('status'))
                <div class="alert alert-success mb-2" role="alert">
                    <strong>Oh snap!</strong> {{ session('status') }}
                </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <fieldset class="form-group position-relative has-icon-left">
                        <input name="email" type="email" class="form-control form-control-lg input-lg" id="user-email" placeholder="{{ __('E-Mail Address') }}" required>
                        <div class="form-control-position">
                            <i class="icon-mail6"></i>
                        </div>
                    </fieldset>
                    @error('email')
                    <div class="alert alert-danger mb-2" role="alert">
                        <strong>Oh snap!</strong> {{ $message }}
                    </div>
                    @enderror
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-lock4"></i> {{ __('Send Password Reset Link') }}</button>
                </form>
            </div>
        </div>
        <div class="card-footer no-border">
            <p class="float-sm-left text-xs-center"><a href="{{route('front.login')}}" class="card-link">Login</a></p>
            <p class="float-sm-right text-xs-center">New to Robust ? <a href="{{route('front.register')}}" class="card-link">Create Account</a></p>
        </div>
    </div>
</div>
@endsection
