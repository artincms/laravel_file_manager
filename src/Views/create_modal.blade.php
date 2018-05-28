<div class="modal fade " id="{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95% !important;max-width: 95% !important;max-height:90% !important;margin: 5px auto !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                    <iframe class="modal_iframe" src="{{$src}}" id="{{LFM_CheckFalseString($section)}}_iframe" style="width: 100%;max-height:  calc(100vh - 187px) ;border: none;height:  calc(100vh - 187px) ;"></iframe>
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
        <div class="modal-content" style="height: calc(100vh - 220px);">
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
    var {{LFM_CheckFalseString($section)}}_available = {{$available}} ;
    $(document).off("click", '#{{$button_id}}');
    $(document).on('click', '#{{$button_id}}', function (e) {
        if ({{LFM_CheckFalseString($section)}}_available > 0)
        {
            $('#{{$modal_id}}').modal();
            $( '#{{LFM_CheckFalseString($section)}}_iframe' ).attr( 'src', function ( i, val ) { return val; });
        }
        else
        {
            $('#create_erro_modal').modal('show');
        }
    });


    //------------------------------------------------------------------------------------//

    $(document).off("click", '#modal_insert_{{LFM_CheckFalseString($modal_id)}}');
    $(document).on('click', '#modal_insert_{{LFM_CheckFalseString($modal_id)}}', function (e) {
        var iframe = $('#{{LFM_CheckFalseString($section)}}_iframe');
        iframe.contents().find("#insert_file").click();
        $('#create_{{$modal_id}}').modal('hide');
    });
  //------------------------------------------------------------------------------------//

    function hidemodal_{{$section}}() {
        $('#{{$modal_id}}').modal('hide');
        $('.modal-backdrop').removeClass();
    }
    //------------------------------------------------------------------------------------//

</script>
