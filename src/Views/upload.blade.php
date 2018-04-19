<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::asset('vendor/laravel_file_manager/bootstrap-fileinput-master/css/fileinput.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/plugins/piexif.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/bootstrap-fileinput-master/js/plugins/sortable.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/plugins/purify.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/bootstrap-fileinput-master/js/fileinput.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/bootstrap-fileinput-master/themes/fa/theme.min.js')}}"></script>

</head>
<body>
<div class="container kv-main">
        <div class="file-loading">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input id="input-708" name="file[]" type="file" multiple>
        </div>
</div>
</body>
<script>
    $("#input-708").fileinput({
        uploadUrl: "{{route('LFM.StoreUploads')}}",
        uploadAsync: false,
        uploadExtraData: {
            category_id:1 ,
            user_id : 1,
            _token: $('#token').val()
        } ,
        maxFileCount: 5
    }).on('filebatchpreupload', function(event, data) {

    });


</script>
</html>