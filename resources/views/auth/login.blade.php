@extends('layouts.admin.login')

@section('footer-js')
<script type="text/javascript">
    $(document).ready(function () {
        $("#login-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8,
                }
            }
        });
    });
</script>
@endsection

@section('content')
<div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
    <div class="card border-grey border-lighten-3 m-0">
        <div class="card-header no-border">
            <div class="card-title text-xs-center">
                <div class="p-1">
                    <!--<img src="../../app-assets/images/logo/robust-logo-dark.png" alt="branding logo">-->
                    <h2><a href="{{route('home-page')}}">mangostudio.tech</a></h2>
                </div>
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>{{ isset($url) ? ucwords($url) : ""}} {{ __('Login') }}</span></h6>
        </div>
        <div class="card-body collapse in">
            <div class="card-block">
                @isset($url)
                <form id="login-form" method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
                    @else
                    <form id="login-form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @endisset
                        @csrf

                        @include('helpers.flash-message')                        

                        @error('email')
                        <div class="alert alert-danger mb-2" role="alert">
                            <strong>Oh snap!</strong> {{ $message }}
                        </div>
                        @enderror
                        <fieldset class="form-group position-relative has-icon-left">
                            <input name="email" value="" type="text" class="form-control form-control-lg input-lg" id="user-name" placeholder="{{ __('E-Mail Address') }}" required>
                            <div class="form-control-position">
                                <i class="icon-head"></i>
                            </div>
                        </fieldset>

                        @error('password')
                        <div class="alert alert-danger mb-2" role="alert">
                            <strong>Oh snap!</strong> {{ $message }}
                        </div>
                        @enderror
                        <fieldset class="form-group position-relative has-icon-left">
                            <input name="password" type="password" class="form-control form-control-lg input-lg  @error('password') is-invalid @enderror" id="user-password" placeholder="Enter Password" required>
                            <div class="form-control-position">
                                <i class="icon-key3"></i>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                                <fieldset>
                                    <input type="checkbox" id="remember-me" class="chk-remember">
                                    <label for="remember-me"> {{ __('Remember Me') }}</label>
                                </fieldset>
                            </div>

                            
                            <div class="col-md-6 col-xs-12 text-xs-center text-md-right">
                                <a href="{{$forget_password}}" class="card-link">{{ __('Forgot Your Password?') }}</a>
                            </div>
                            
                        </fieldset>
                        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-unlock2"></i> Login</button>
                    </form>
            </div>
        </div>
        <div class="card-footer">
            <div class="">
                <center><p class="text-xs-center m-0">New to Robust? <a href="{{$register}}" class="card-link">Sign Up</a></p></center>
            </div>
        </div>
    </div>
</div>
@endsection
