<!doctype html>
<html lang="app()->getLocale() " style="direction: @if(in_array(app()->getLocale() ,config('laravel_file_manager.lang_rtl'))) rtl @else ltr @endif">
<head>
    <title>@yield('page_title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    {{--add style sheet--}}
    @if(in_array(app()->getLocale(),config('laravel_file_manager.lang_rtl')))
        <link href="{{ asset('vendor/laravel_file_manager/css/build/init_core_rtl.min.css') }}" rel="stylesheet" rel="stylesheet">
        <link href="{{ asset('vendor/laravel_file_manager/css/rtl/custom_rtl.css') }}" rel="stylesheet" rel="stylesheet">
    @else
        <link href="{{ asset('vendor/laravel_file_manager/css/build/init_core.min.css') }}" rel="stylesheet" rel="stylesheet">
        <link href="{{ asset('vendor/laravel_file_manager/css/custom.css') }}" rel="stylesheet" rel="stylesheet">
    @endif
    @yield('add_css')
    {{--add js file--}}
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/js/build/init_core.min.js')}}"></script>
    <script type="text/javascript">
        var base_url = '{{ url('/') }}'
    </script>

    @yield('add_js')

</head>
<body style="padding: 1%;">
    <div class="container-fluid">
        <div class="row">
                @yield('content')
        </div>
    </div>
    @yield('javascript')
</body>
</html>