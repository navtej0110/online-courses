<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.admin.includes.head')
    <body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">

        <!-- navbar-fixed-top-->
        @include('layouts.admin.includes.header-nav')

        <!-- main menu-->
        @include('layouts.admin.includes.sidebar')
        <!-- / main menu-->

        <div class="app-content content container-fluid">
            <div class="content-wrapper">
                <!--@include('layouts.admin.includes.breadcrumbs')-->
                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- footer -->
        @include('layouts.admin.includes.footer')

    </body>
</html>
