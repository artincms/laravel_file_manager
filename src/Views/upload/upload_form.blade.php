@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link rel="stylesheet" href="{{URL::asset('vendor/laravel_file_manager/css/build/fileinput.min.css')}}">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{asset('vendor/laravel_file_manager/js/build/fileinput.min.js')}}"></script>
@endsection
@section('content')
    <div class="container kv-main" id="{{$section}}_kv_main">
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
    if (parent.{{LFM_CheckFalseString($section)}}_available > 0)
    {
        $("#input-708").fileinput({
            theme: "fa",
            language: "{{app()->getLocale()}}",
            browseLabel : '@lang('filemanager.browse_label')',
            uploadUrl: "{{route('LFM.StoreDirectUploads')}}",
            uploadAsync: false,
            uploadExtraData: {
                section : '{{LFM_CheckFalseString($section)}}',
                callback : '{{LFM_CheckFalseString($callback)}}',
                _token: $('#token').val()
            },
            delete:false,
            @if($section && $section !='false' && $options !=false)
            maxFileCount: parent.{{LFM_CheckFalseString($section)}}_available,
            allowedFileExtensions: [{!! $true_ext !!}],
            @else
            allowedFileExtensions:[{!!$ext !!}],
            @endif
            elErrorContainer: "#errorBlock",
            browseClass: "btn btn-success",
            removeClass: "btn btn-danger",
            removeLabel: '@lang('filemanager.clear')',
            removeIcon: "<i class=\"glyphicon glyphicon-refresh\"></i> ",
            uploadClass: "btn btn-info",
            uploadLabel: "@lang('filemanager.upload')",
            uploadIcon: "<i class=\"glyphicon glyphicon-upload\"></i> "
        }).on('filebatchpreupload', function(event, data) {

        }).on('filebatchpreupload', function(event, data, id, index) {
            $('#kv-success-2').html('<h4>@lang('filemanager.upload_status')</h4><ul></ul>').hide();
            $('#kv-error-2').html('<h4>@lang('filemanager.error_status')</h4><ul></ul>').hide();
        }).on('filebatchuploadsuccess', function(event, data) {
            complete(data);
            parent.{{LFM_CheckFalseString($section)}}_available = data.response.{{LFM_CheckFalseString($section)}}.available ;
            if (typeof parent.refresh !== 'undefined') {
                parent.refresh();
            }
            @if ($callback)
            if (typeof parent.{{$callback}} !== 'undefined') {
                parent.{{$callback}}(data.response);
            }
            @endif


        });
    }
    else
    {
       $('#{{$section}}_kv_main').html('<h3>You reach your maximum file upload</h3><br/><h4>for more upload you should remove previous upload file</h4>');
    }
    function complete(data) {
        console.log(data);
        var out = '';
        $.each(data.response.{{$section}}.data, function (index, value) {
            if (value.success) {
                var fname = value.file.originalName;
                out = out + '<div class="alert alert-success">' + '@lang('filemanager.uploaded_file') # ' + (index + 1) + ' - ' + fname + ' @lang('filemanager.successfully').' + '</div>';
            }
            else {
                var fname = value.originalName;
                out = out + '<div class="alert alert-danger">' + '@lang('filemanager.error_uploaded_file') # ' + (index + 1) + ' - ' + fname ;
                out += '<p>' + value.error + '</p></div>';
            }
        });


        swal({
            title: '<h4>@lang('filemanager.upload_status')</h4>',
            html: out,
            showCloseButton: false,
            focusConfirm: false,
            confirmButtonText:
                'More Upload',
            showCancelButton: true,
            cancelButtonText: 'Close',
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
        $('#{{$section}}_kv_main').append(generate_loader_html('@lang('filemanager.please_wait')'));
        document.location.reload() ;
    }
    function generate_loader_html(loading_text) {
        var loader_html = '' +
            '<div class="total_loader">' +
            '   <div class="total_loader_content" style="">' +
            '       <div class="spinner_area">' +
            '           <div class="spinner_rects">' +
            '               <div class="rect1"></div>' +
            '               <div class="rect2"></div>' +
            '               <div class="rect3"></div>' +
            '               <div class="rect4"></div>' +
            '               <div class="rect5"></div>' +
            '           </div>' +
            '       </div>' +
            '       <div class="text_area">' + loading_text + '</div>' +
            '   </div>' +
            '</div>';
        return loader_html;
    }
</script>
@endsection



