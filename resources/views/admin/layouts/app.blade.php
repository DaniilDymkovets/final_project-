<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Stules UI -->
    <link href="{{ asset('css/ui/jquery-ui.css') }}" rel="stylesheet">
    
        @stack('styles')
        
    <link href="{{ asset('css/global.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
        @yield('headstyles') 
        
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/ui/jquery-ui.js') }}"></script>
    @stack('upscripts')
    @yield('headscript')
</head>
<body>
    <div id="app">
        

        @include('admin.layouts.navbar')
        
        @if (Auth::guard('admin')->check())
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-3 col-lg-2">
                        @include('admin.layouts.leftmenu')
                    </div>

                    <div class="col-md-9 col-lg-10">
                        @yield('content')
                    </div>
                </div>
            </div>           
        @else
            <div class="container">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        @endif
    </div>

@stack('scripts')
@yield('footerscript')
</body>
</html>
