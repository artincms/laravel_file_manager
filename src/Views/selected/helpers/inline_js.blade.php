<script>
    $(document).off("click", '.{{$section}}_trash_insert');
    $(document).on('click', '.{{$section}}_trash_insert', function (e) {
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
            success: function (result) {
                if (result.success)
                {
                    $('#' + section + '_' + file_id + '_trash_insert').remove();
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