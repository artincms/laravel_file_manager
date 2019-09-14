<script>
    $( document ).ready(function() {
        {{LFM_CheckFalseString($section)}}available  ={{LFM_CheckAllowInsert($section)['available']}} ;
    });
    $(document).off("click", '#{{$section}}_trash_inserted');
    $(document).on('click', '#{{$section}}_trash_inserted', function (e) {
        e.preventDefault();
        var file_id = $(this).attr('data-file_id');
        var section = $(this).attr('data-section');
        delete_inserted_item_from_session_{{$section}}(section, file_id);
    });

    function delete_inserted_item_from_session_{{$section}}(section, file_id) {
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.DeleteSessionInsertItem')}}",
            data: {
                section: section,
                file_id: file_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.success)
                {
                    $('#' + section + '_' + file_id + '_trash_insert_li').remove();
                    {{LFM_CheckFalseString($section,false,true)}}available  =result.{{LFM_CheckFalseString($section)}}.available ;
                }
                else
                {
                    alert('has error: please check the console log');
                }
            },
            error: function (e) {
                alert('has error: please check the console log');
            }
        });
    }
</script>