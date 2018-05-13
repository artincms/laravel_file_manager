@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link rel="stylesheet" href="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/css/fileinput.css')}}">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/js/plugins/sortable.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/js/plugins/piexif.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/js/plugins/purify.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/js/fileinput.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/bootstrap-fileinput-master/themes/fa/theme.min.js')}}"></script>
@endsection
@section('content')
    <div class="container kv-main">
        <div class="file-loading">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            @if($section != 'false')
                @php($true_ext = sprintf("'%s'",implode("','",$options['true_file_extension'])))
                @php($accept_ext = '.'.implode(",.",$options['true_file_extension']))
                <input id="input-708" name="file[]" type="file" multiple accept="{!! $accept_ext !!}">
            @else
                <input id="input-708" name="file[]" type="file" multiple>
            @endif
        </div>
        <div id="kv-error-2" style="margin-top:10px;display:none"></div>
        <div id="kv-success-2" class="alert alert-success" style="margin-top:10px;display:none"></div>
    </div>
    <script type="text/javascript">
        //-----------------------------------------------------------------------------------------------------//
        $("#input-708").fileinput({
            theme: "fa",
            uploadUrl: "{{route('LFM.StoreUploads')}}",
            uploadAsync: false,
            uploadExtraData: {
                category_id: {{$category_id}} ,
                _token: $('#token').val()
            },
            uploadAsync: true,
            @if($section && $section !='false')
            maxFileCount: '{{$options['max_file_number']}}',
            allowedFileExtensions: [{!! $true_ext !!}],
            @endif
            elErrorContainer: "#errorBlock",
            previewFileType: "image",
            browseClass: "btn btn-success",
            removeClass: "btn btn-danger",
            removeLabel: "Clear",
            removeIcon: "<i class=\"glyphicon glyphicon-refresh\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "Upload",
            uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> "

        }).on('fileuploaded', function (event, data, id, index) {
            if (data.response.result.is_picture == true && data.response.success == true) {
                var id = data.response.result.ID;
                var btn_route = '/LFM/EditPicture/' + id;
                var btns = '<a href=' + btn_route + ' class="kv-cust-btn btn btn-kv btn-secondary" title="Edit"{dataKey}>' +
                    '<i class="glyphicon glyphicon-edit"></i>' +
                    '</a>';
                $(".file-footer-buttons").append(btns);
            }

            @if ($callback) {
                if(parent.{{$callback}})
                {
                    parent.{{$callback}}();

                }
            }
            @endif
        });
        $('#cancel_btn').off('click');
        $('#cancel_btn').on('click', function () {
            parent.$('.modal').modal('hide');
        });
    </script>
@endsection



