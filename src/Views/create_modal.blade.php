<div class="modal fade " id="create_{{$modal_id}}" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="modal_iframe" src="{{$src}}"></iframe>
                </div>
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
    $(document).off("click", '#'+insert_button_id);
    $(document).on('click', '#'+insert_button_id, function (e) {
        var iframe = $('iframe.modal_iframe');
        iframe.contents().find("#insert_file").click();
    });

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
</script>
