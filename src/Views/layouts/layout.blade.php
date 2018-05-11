<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    {{--add style sheet--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/sms_ir-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/sms_ir-rtl.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/custom.css') }}" rel="stylesheet">
    {{--add js file--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    @yield('content')
    @yield('javascript')
</body>
</html>