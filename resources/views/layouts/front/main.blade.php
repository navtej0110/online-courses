<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @yield('meta-info')
        @include('layouts.front.includes.head')
    </head>
    <body id="page-top">
        @include('layouts.front.includes.topmenu')
        @yield('content')
        @include('layouts.front.includes.footer')
    </body>
</html>
