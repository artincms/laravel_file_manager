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
{{--
    {{dd(LFM_ConvertMimeTypeToExt(config('laravel_file_manager.allowed')))}}
--}}
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
        }).on('filebatchuploadsuccess', function(event, data) {
            var out = '';
            $.each(data.response, function(index, value) {
                if(value.success)
                {
                    var fname = value.result.OrginalFileName;
                    out = out + '<div class="alert alert-success">' + 'Uploaded file # ' + (index + 1) + ' - '  +  fname + ' successfully.' + '</div>';
                    /*$('#kv-success-2 ul').append(out);
                    $('#kv-success-2').fadeIn('slow');*/
                }
                else
                {
                    var fname = value.OrginalFileName;
                    out = out + '<div class="alert alert-danger">' + 'Eror Uploaded file # ' + (index + 1) + ' - '  +  fname + '</div>';
                  /*  $('#kv-error-2 ul').append(out);
                    $('#kv-error-2').fadeIn('slow');*/
                }
                if(typeof parent.refresh !== 'undefined')
                {
                    parent.refresh() ;
                }

            });
            swal({
                title: '<h4>Upload Status</h4>',
                html:out,
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> OK',
            })
        });
        //-----------------------------------------------------------------------------------------------------------//
        $('#cancel_btn').off('click');
        $('#cancel_btn').on('click', function () {
            parent.$('.modal').modal('hide');
        });
    </script>
@endsection



