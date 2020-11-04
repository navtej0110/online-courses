@extends('layouts.admin.login')

@section('footer-js')
<script type="text/javascript">
    $(document).ready(function () {
        $("#register-form").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                }
            }
        });
    });
</script>
@endsection

@section('content')
<div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1 box-shadow-2 p-0">
    <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
        <div class="card-header no-border">
            <div class="card-title text-xs-center">
                <!--<img src="../../app-assets/images/logo/robust-logo-dark.png" alt="branding logo">-->
                <h2><a href="{{route('home-page')}}">mangostudio.tech</a></h2>
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>{{ isset($url) ? ucwords($url) : ""}} {{ __('Register') }}</span></h6>
        </div>
        <div class="card-body collapse in">	
            <div class="card-block">
                @isset($url)
                <form id="register-form" method="POST" action='{{ url("register/$url") }}' aria-label="{{ __('Register') }}">
                    @else
                    <form id="register-form" method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                        @endisset
                        @csrf
                        
                        @include('helpers.flash-message')
                        
                        @error('name')
                        <div class="alert alert-danger mb-2" role="alert">
                            <strong>Oh snap!</strong> {{ $message }}
                        </div>
                        @enderror
                        <fieldset class="form-group position-relative has-icon-left mb-1">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-lg input-lg" id="name" placeholder="{{ __('Name') }}" required>
                            <div class="form-control-position">
                                <i class="icon-head"></i>
                            </div>
                        </fieldset>
                        
                        @error('email')
                        <div class="alert alert-danger mb-2" role="alert">
                            <strong>Oh snap!</strong> {{ $message }}
                        </div>
                        @enderror
                        <fieldset class="form-group position-relative has-icon-left mb-1">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg input-lg" id="user-email" placeholder="{{ __('E-Mail Address') }}" required>
                            <div class="form-control-position">
                                <i class="icon-mail6"></i>
                            </div>
                        </fieldset>
                        
                        <fieldset class="form-group position-relative has-icon-left">
                            <input type="password" name="password" class="form-control form-control-lg input-lg @error('password') is-invalid @enderror" id="user-password" placeholder="{{ __('Password') }}" required>
                            <div class="form-control-position">
                                <i class="icon-key3"></i>
                            </div>
                        </fieldset>
                        
                        @error('password')
                        <div class="alert alert-danger mb-2" role="alert">
                            <strong>Oh snap!</strong> {{ $message }}
                        </div>
                        @enderror    
                        <fieldset class="form-group position-relative has-icon-left">
                            <input type="password"  name="password_confirmation" class="form-control form-control-lg input-lg @error('password') is-invalid @enderror" id="user-password" placeholder="{{ __('Confirm Password') }}" required>
                            <div class="form-control-position">
                                <i class="icon-key3"></i>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-unlock2"></i> {{ __('Register') }}</button>
                    </form>
            </div>
            <p class="text-xs-center">Already have an account ? <a href="{{$login}}" class="card-link">Login</a></p>
        </div>
    </div>
</div>
@endsection
