<script type="text/javascript">

    //---------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#btn_submit_category');
    $(document).on('click', '#btn_submit_category', function (e) {
        e.preventDefault() ;
        var formElement = document.querySelector('#create_category_form');
        var formData = new FormData(formElement);
        $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
        @if (!$callback)
        category_save(formData);
        @else
        category_save(formData, '{{$callback}}');
        @endif
    });
    $(document).off("click", '#btn_submit_category_close');
    $(document).on('click', '#btn_submit_category_close', function (e) {
        e.preventDefault() ;
        var formElement = document.querySelector('#create_category_form');
        var formData = new FormData(formElement);
        $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
        @if (!$callback)
        category_save(formData,false,true);
        @else
        category_save(formData, '{{$callback}}',true);
        @endif
    });

    function category_save(FormData,callback,close) {
        callback = callback || false ;
        close = close || false ;
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.StoreCategory')}}",
            data: FormData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success == true) {
                    parent.refresh() ;
                    if(close)
                    {
                        parent.$('#create_category_modal').modal('hide');
                    }

                   @if($callback)
                       if(parent.callback)
                        {
                            parent.callback();
                        }

                   @endif
                   document.location.reload();
                }
            },
            error: function (e) {
                $('.total_loader').remove();
                $('#show_error').removeClass('hidden');
                $('#show_edit_category_error').html('');
                $.each(e.responseJSON.errors,function (index,value) {
                    $('#show_edit_category_error').append('<li><span>'+index+':'+value+'</li>');
                });
            }
        });
    }

    //---------------------------------------------------------------------------------------------------------//
    $('.select_category').select2({

    });

    function generate_loader_html(loading_text) {
        var loader_html = '' +
            '<div class="total_loader">' +
            '   <div class="total_loader_content" style="">' +
            '       <div class="spinner_area">' +
            '           <div class="spinner_rects">' +
            '               <div class="rect1"></div>' +
            '               <div class="rect2"></div>' +
            '               <div class="rect3"></div>' +
            '               <div class="rect4"></div>' +
            '               <div class="rect5"></div>' +
            '           </div>' +
            '       </div>' +
            '       <div class="text_area">' + loading_text + '</div>' +
            '   </div>' +
            '</div>';
        return loader_html;
    }
    //---------------------------------------------------------------------------------------------------------//
</script>