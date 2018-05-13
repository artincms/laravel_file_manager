<!doctype html>
<html>
<head>
    <title>@yield('page_title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    {{--add style sheet--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/custom.css') }}" rel="stylesheet" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/sweetalert2.min.css') }}" rel="stylesheet" rel="stylesheet">
    @yield('add_css')
    {{--add js file--}}
    <script type="text/javascript">
        var base_url = '{{ url('/') }}'
    </script>
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/packages/jquery/jquery-3.3.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/packages/bootstrap/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/csrf_token_ajax_header_setup.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/js/sweetalert2.min.js')}}"></script>
    @yield('add_js')

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="media-manager col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
    @yield('javascript')
</body>
</html>