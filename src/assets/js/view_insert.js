$(document).off("click", '#trash_insert');
$(document).on('click', '#trash_insert', function (e){
    e.preventDefault() ;
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-section');
    res = delete_inserted(name,id) ;
});

function delete_inserted(name,id) {
    $.ajax({
        type: "POST",
        url: "{{route('LFM.DeleteSelectedPostId')}}",
        data :{
            name:name,
            id : id
        },
        success: function (result) {

        },
        error: function (e) {

        }
    });


}