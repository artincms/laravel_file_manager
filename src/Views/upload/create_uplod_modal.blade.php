<div class="container-fluid">
    <div id="{{$result_area_id}}">

    </div>
</div>
<div class="modal fade " id="create_upload_{{$section}}_{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95% !important;max-width: 95% !important;max-height:90% !important;margin: 5px auto !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="{{$section}}_body_upload_form" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                <iframe class="modal_iframe" src="{{$src}}" id="{{LFM_CheckFalseString($section)}}_iframe_upload" style="width: 100%;max-height:  calc(100vh - 187px) ;border: none;height:  calc(100vh - 187px) ;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="create_erro_modal_upload" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="height: calc(100vh - 220px);">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                <h2>You Reach Your Maximum File Upload</h2>
                <h5>for upload new file you should remove previus uploaded file</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var {{LFM_CheckFalseString($section)}}_available = {{$available}} ;
    var insert_button_id = 'modal_insert_{{$section.'_'.LFM_CheckFalseString($modal_id)}}';
    var FrameID = "#create_upload_{{$section}}_{{$modal_id}}" ;
    var button_modal_id = '{{$button_id}}';
    $(document).off("click", '#'+button_modal_id);
    $(document).on('click', '#'+button_modal_id, function (e) {
        if ({{LFM_CheckFalseString($section)}}_available > 0)
        {
            $(FrameID).modal();
            $( '#{{LFM_CheckFalseString($section)}}_iframe_upload' ).attr( 'src', function ( i, val ) { return val; });
        }
        else
        {
            $('#create_erro_modal_upload').modal('show');
        }
    });
    //---------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#'+insert_button_id);
    $(document).on('click', '#'+insert_button_id, function (e) {
        var iframe = $('#{{LFM_CheckFalseString($section)}}_iframe_upload');
        iframe.contents().find("#insert_file").click();
        $('#create_{{$modal_id}}').modal('hide');
    });
    //------------------------------------------------------------------------------------//
    function hidemodal() {
        $('#close_button_{{LFM_CheckFalseString($modal_id)}}').click();
        $('.modal-backdrop').removeClass();
    }
    //-------------------------------------------------------------------------------------//
    function clearupload() {
        $('#{{$section}}_body_upload_form').append(generate_loader_html('لطفا منتظر بمانید...'));
    }
    //-------------------------------------------------------------------------------------//
    function {{$callback}}(data) {
        @if($result_area_id)
        $('#{{$result_area_id}}').html(data.{{$section}}.view.{{$options['show_file_uploaded']}})
        @endif
    }
</script>