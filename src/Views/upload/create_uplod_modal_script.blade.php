<script type="text/javascript">
    window['{{LFM_CheckFalseString($section,false,true)}}available'] = {{$available}} ;
    var insert_button_id_{{$section}} = 'modal_insert_{{$section.'_'.LFM_CheckFalseString($modal_id)}}';
    var FrameID_{{$section}} = "#create_upload_{{$section}}_{{$modal_id}}" ;
    $(document).off("click", '#{{$button_id}}');
    $(document).on('click', '#{{$button_id}}', function (e) {
        var src_{{$section}} = $(this).attr('data-href');
        var iframe_{{$section}} = $('#{{LFM_CheckFalseString($section)}}_iframe_upload');
        iframe_{{$section}}.contents().find("body").html('');
        iframe_{{$section}}.attr("src",src_{{$section}});
        if ({{LFM_CheckFalseString($section,false,true)}}available > 0)
        {
            $(FrameID_{{$section}}).modal('show');
            iframe_{{LFM_CheckFalseString($section)}}.attr( 'src', function ( i, val ) { return val; });
        }
        else
        {
            $('#create_erro_modal_upload').modal('show');
        }
    });
    //---------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#'+insert_button_id_{{$section}});
    $(document).on('click', '#'+insert_button_id_{{$section}}, function (e) {
        var iframe_{{$section}} = $('#{{LFM_CheckFalseString($section)}}_iframe_upload');
        iframe_{{$section}}.contents().find("#insert_file").click();
        $('#create_{{$modal_id}}').modal('hide');
    });
    //------------------------------------------------------------------------------------//
    function hidemodal() {
        $('#close_button_{{LFM_CheckFalseString($modal_id)}}').click();
        $('.modal-backdrop').removeClass();
    }
    //-------------------------------------------------------------------------------------//
    function clearupload() {
        $('#{{$section}}_body_upload_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
    }
    //-------------------------------------------------------------------------------------//

    window['call_back_function{{$section}}'] = function(data) {
        @if($callback)
            window['{{$callback}}'](data);
        @endif
    }
</script>