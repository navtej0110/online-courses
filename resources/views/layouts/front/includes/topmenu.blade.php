<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container-fluid">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Mangostudio.Tech</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{{route('home-page')}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{{route('course.list')}}">Courses</a>
                </li>
                <?php if (Auth::check() && $logged_in_user_guard == 'web'): ?>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{route('front.profile')}}">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{route('front.dashboard')}}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Hi! {{$logged_in_user->name}}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                <?php elseif (Auth::guard('admin')->check() && $logged_in_user_guard == 'admin'): ?>   
                    <li class="nav-item">
                        <div class="dropdown show">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Hi Admin! {{$logged_in_user}}
                            </a>
                           
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{route('admin.home')}}">
                                    Go To Admin
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </div>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>    

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{route('front.register')}}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="{{route('front.login')}}">Login</a>
                    </li>
                <?php endif; ?>
                <!--<li class="nav-item">
                   <select class="selectpicker" data-width="fit">
                       <option <?php echo app()->getLocale() == "en" ? "selected" : ""; ?> value="en" data-content='<span class="flag-icon flag-icon-us"></span> English'>English</option>
                       <option <?php echo app()->getLocale() == "es" ? "selected" : ""; ?> value="es" data-content='<span class="flag-icon flag-icon-es"></span> Español'>Español</option>
                    </select> 
                </li>-->
            </ul>
        </div>
    </div>
</nav>
