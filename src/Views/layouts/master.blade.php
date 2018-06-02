<!doctype html>
<html>
<head>
    <title>@yield('page_title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    {{--add style sheet--}}
    <link href="{{ asset('vendor/laravel_file_manager/css/build/init_core.min.css') }}" rel="stylesheet" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/custom.css') }}" rel="stylesheet" rel="stylesheet">

    @yield('add_css')
    {{--add js file--}}
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/js/build/init_core.min.js')}}"></script>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}'
    </script>

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