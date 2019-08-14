<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cabinet.css') }}" rel="stylesheet">
    @stack('styles')
    @yield('headerstyles')
    <!-- cabinet app -->
</head>
<body>
    <div id="app">
        @include('cabinet.layouts.navbar')

        <div class="cabinet-page">
            <div class="container">
                <div class="table">
                    @if (Auth::guard()->check())
                        @include('cabinet.layouts.leftmenu')
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
@yield('footerscript')
</body>
</html>
