@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link rel="stylesheet" href="{{URL::asset('vendor/laravel_file_manager/css/build/fileinput.min.css')}}">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/js/build/fileinput.min.js')}}"></script>
@endsection
@section('content')
    <div class="container kv-main">
        <div class="file-loading">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            @if($section != 'false' && $options !=false)
                @php($true_ext = sprintf("'%s'",implode("','",$options['true_file_extension'])))
                @php($accept_ext = '.'.implode(",.",$options['true_file_extension']))
                <input id="input-708" name="file[]" type="file" multiple accept="{!! $accept_ext !!}">
            @else
                @php($extensions = LFM_ConvertMimeTypeToExt(config('laravel_file_manager.allowed')))
                @php($ext = $extensions['ext'])
                @php($accept_ext =$extensions['accept'] )
                <input id="input-708" name="file[]" type="file" multiple  accept="{!! $accept_ext !!}">
            @endif
        </div>
        <div id="kv-error-2"  class="alert alert-danger" style="margin-top:10px;display:none"></div>
        <div id="kv-success-2" class="alert alert-success" style="margin-top:10px;display:none"></div>
    </div>
    <script type="text/javascript">
        //-----------------------------------------------------------------------------------------------------//
        $("#input-708").fileinput({
            theme: "fa",
            uploadUrl: "{{route('LFM.StoreUploads')}}",
            uploadAsync: false,
            uploadExtraData: {
                category_id: '{{$category_id}}' ,
                _token: $('#token').val()
            },
            delete:false,
            @if($section && $section !='false' && $options !=false)
            maxFileCount: '{{$options['max_file_number']}}',
            allowedFileExtensions: [{!! $true_ext !!}],
            @else
            allowedFileExtensions:[{!!$ext !!}],
            @endif
            elErrorContainer: "#errorBlock",
            browseClass: "btn btn-success",
            removeClass: "btn btn-danger",
            removeLabel: "Clear",
            removeIcon: "<i class=\"glyphicon glyphicon-refresh\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "Upload",
            uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> "
        }).on('filebatchpreupload', function(event, data, id, index) {
            $('#kv-success-2').html('<h4>Upload Status</h4><ul></ul>').hide();
            $('#kv-error-2').html('<h4>Error Status</h4><ul></ul>').hide();
            console.log('d');
        }).on('filebatchuploadsuccess', function(event, data) {
           complete(data);

        });

        function complete(data) {
            var out = '';
            console.log(data);
            $.each(data.response, function (index, value) {
                if (value.success) {
                    var fname = value.file.originalName;
                    out = out + '<div class="alert alert-success">' + '@lang('filemanager.uploaded_file') # ' + (index + 1) + ' - ' + fname + ' @lang('filemanager.successfully').' + '</div>';
                }
                else {
                    var fname = value.originalName;
                    out = out + '<div class="alert alert-danger">' + '@lang('filemanager.error_uploaded_file') # ' + (index + 1) + ' - ' + fname ;
                }
                if (typeof parent.refresh !== 'undefined') {
                    parent.refresh();
                }

            });
            @if ($callback)
            if (typeof parent.{{$callback}} !== 'undefined') {
                parent.{{$callback}}(data);
            }
            @endif
            swal({
                title: '<h4>@lang('filemanager.upload_status')</h4>',
                html: out,
                showCloseButton: false,
                focusConfirm: false,
                confirmButtonText:'@lang('filemanager.more_upload')',
                showCancelButton: true,
                cancelButtonText: '@lang('filemanager.close')',
                cancelButtonClass: 'close_upload',
                confirmButtonClass: 'clear_upload',
            }).then(function (result) {
                if (result.dismiss == 'cancel') {
                    parent.$('.modal').modal('hide');
                }
                else {
                    clearupload();
                }
            });
        }

        //-----------------------------------------------------------------------------------------------------------//
        $('#cancel_btn').off('click');
        $('#cancel_btn').on('click', function () {
            parent.$('.modal').modal('hide');
        });
        function clearupload() {
            $('.fileinput-remove-button').click() ;
        }
    </script>
@endsection



