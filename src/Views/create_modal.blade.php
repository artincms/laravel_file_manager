<div class="modal fade " id="create_{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95% !important;max-width: 95% !important;max-height:90% !important;margin: 5px auto !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                    <iframe class="modal_iframe" src="{{$src}}" style="width: 100%;max-height:  calc(100vh - 187px) ;border: none;height:  calc(100vh - 187px) ;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_button_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal">cancel</button>
                <button type="button" class="btn btn-primary" id="modal_insert_{{LFM_CheckFalseString($modal_id)}}" >{{$button_content}}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="create_erro_modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                <h2>You Reach Your Maximum File Inserted</h2>
                <h5>for insert new file you should remove previus inserted file</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var {{$section}}_available = {{$available}} ;
    var insert_button_id = 'modal_insert_{{LFM_CheckFalseString($modal_id)}}';
    var FrameID = "#create_{{$modal_id}}" ;
    var button_modal_id = '{{$button_id}}';
    $(document).off("click", '#'+button_modal_id);
    $(document).on('click', '#'+button_modal_id, function (e) {

        if ({{$section}}_available > 0)
        {
            $(FrameID).modal();
        }
        else
        {
            $('#create_erro_modal').modal('show');
        }
    });

    //------------------------------------------------------------------------------------//

    //------------------------------------------------------------------------------------//

    $(document).off("click", '#'+insert_button_id);
    $(document).on('click', '#'+insert_button_id, function (e) {
        var iframe = $('iframe.modal_iframe');
        iframe.contents().find("#insert_file").click();
        $('#create_{{$modal_id}}').modal('hide');
    });
  //------------------------------------------------------------------------------------//

    function hidemodal() {
        $('#close_button_{{LFM_CheckFalseString($modal_id)}}').click();
        $('.modal-backdrop').removeClass();
    }
    //------------------------------------------------------------------------------------//

</script>
