<div class="modal fade " id="create_{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 95% !important;max-height:90% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(1000vh - 512px) ;">
                    <iframe class="modal_iframe" src="{{$src}}" style="max-height: calc(100vh - 212px);width:100%;"></iframe>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_button_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal">cancel</button>
                <button type="button" class="btn btn-primary" id="modal_insert_{{LFM_CheckFalseString($modal_id)}}" >{{$button_content}}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var insert_button_id = 'modal_insert_{{LFM_CheckFalseString($modal_id)}}';
    var FrameID = "#create_{{$modal_id}}" ;
    var button_modal_id = '{{$button_id}}';
    $(document).off("click", '#'+button_modal_id);
    $(document).on('click', '#'+button_modal_id, function (e) {
        $(FrameID).modal();
        $( '.modal_iframe' ).attr( 'src', function ( i, val ) { return val; });


    });
    //------------------------------------------------------------------------------------//

    $(document).off("click", '#'+insert_button_id);
    $(document).on('click', '#'+insert_button_id, function (e) {
        var iframe = $('iframe.modal_iframe');
        iframe.contents().find("#insert_file").click();
    });
  //------------------------------------------------------------------------------------//
    $(document).off("click", '#trash_insert');
    $(document).on('click', '#trash_insert', function (e) {
        var file_id = $(this).attr('data-id') ;
        var res = trash_selected_file(file_id);
        if (res.success ==true)
        {
            $(this).parent('div').addClass('hidden');
        }
    });

    function trash_selected_file(file_id) {
        var res ='';
        $.ajax({
            type: "POST",
            url: "{{route('LFM.DeleteSessionInsertItem')}}",
            dataType: "json",
            async: false,
            data :{
                file_id:file_id,
                section : '{{$section}}'
            },
            success: function (result) {
                res = result ;
            },
            error: function (e) {
                res =  false ;
            }
        });
        return res ;
    }
    function hidemodal() {
        $('#close_button_{{LFM_CheckFalseString($modal_id)}}').click();
        $('.modal-backdrop').removeClass();
    }
    //------------------------------------------------------------------------------------//

</script>
