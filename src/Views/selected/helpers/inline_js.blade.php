<script>
    $(document).off("click", '#{{$section}}_trash_inserted');
    $(document).on('click', '#{{$section}}_trash_inserted', function (e) {
        e.preventDefault();
        var file_id = $(this).attr('data-file_id');
        var section = $(this).attr('data-section');
        delete_inserted_item_from_session(section, file_id);
    });

    function delete_inserted_item_from_session(section, file_id) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.DeleteSessionInsertItem')}}",
            data: {
                section: section,
                file_id: file_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.success)
                {
                    $('#' + section + '_' + file_id + '_trash_insert_li').remove();
                    {{LFM_CheckFalseString($section)}}_available  =result.{{LFM_CheckFalseString($section)}}.available ;
                    console.log(result.{{LFM_CheckFalseString($section)}} );
                }
                else
                {
                    alert('has error: please check the console log');
                    console.log(result);
                }
            },
            error: function (e) {
                alert('has error: please check the console log');
                console.log(e);
            }
        });
    }
</script>