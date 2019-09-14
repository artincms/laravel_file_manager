<script type="text/javascript">
    //------------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#show_list');
    $(document).on('click', '#show_list', function (e) {
        $('#show_list_tab').tab('show') ;
        $('#refresh_page').attr('data-type','list') ;
    });
//-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '.link_to_category');
    $(document).on('click', '.link_to_category', function (e) {
        e.preventDefault();
        var type = $('#refresh_page').attr('data-type');
        if(type == 'search')
        {
            $('#refresh_page').attr('data-type','list');
        }
        $('.media-content').append(generate_loader_html('@lang('filemanager.please_wait')'));
        var id = $(this).attr('data-id');
        show_category(id);
    });

    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#select_all');
    $(document).on('click', '#select_all', function (e) {
        set_selected_all();
        var i = 0 ;
        var type = $('refresh_page').attr('data-type');
        items = get_selected(['file'],type) ;
        $('.toggle_select').attr("id", "select_none");
        set_inserted_to_button(items.length);
    });


    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#select_none');
    $(document).on('click', '#select_none', function (e) {
        $('.check').each(function () {
            $(this).removeClass('selected');
            $(this).prop('checked', false);
        });
        $('.toggle_select').attr("id","select_all");
        set_inserted_to_button(0);
    });
    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#sweet_image');
    $(document).on('click', '#sweet_image', function (e) {
        e.preventDefault() ;
        var id = $(this).attr('data-id');
        var title = $(this).attr('title');
        var user_name = $(this).attr('data-user-name');
        var created_date = $(this).attr('data-created-date');
        var updated_date = $(this).attr('data-updated-date');
        var type = $(this).attr('data-type') ;
        var icon = $(this).attr('data-icon') ;
        var src_original = download_url(id,'original');
        var src_large = download_url(id,'large');
        var src_medium = download_url(id,'medium');
        var src_small = download_url(id,'small');
        var src_img = download_url(id,'small','404.png',100,250,200);
        var insert = '{{$insert}}'  ;
        var humman_size = $(this).attr('data-humman_size');
        var humman_size_large = $(this).attr('data-humman_size_large');
        var humman_size_medium = $(this).attr('data_humman_size_medium');
        var humman_size_small = $(this).attr('data-humman_size_small');
        var public_original_path = $(this).attr('data-public-original-path');
        var public_large_path = $(this).attr('data-public-large-path');
        var public_medium_path = $(this).attr('data-public-medium-path');
        var public_small_path = $(this).attr('data-public-small-path');
        var footer = '' +
            '<div class="swal2-actions" style="display: flex;">' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100 padding_top_9" id="basic-addon1">@lang('filemanager.width')</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="Width" aria-label="width" name="width_picture" aria-describedby="basic-addon1" value="0" id="change_width">' +
            '</div>' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100 padding_top_9" id="basic-addon1">@lang('filemanager.height')</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="Height" aria-label="height" name="height_picture" aria-describedby="basic-addon1" value="0" id="change_height">' +
            '</div>' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100 padding_top_9" id="basic-addon1">@lang('filemanager.quality')</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="quality" aria-label="quality" name="quality_picture" aria-describedby="basic-addon1" value="100" id="change_quality">' +
            '</div>' +
            '   <button type="button" class="swal2-confirm btn pull-right swal2-styled font-size-14" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);" data-id="'+id+'" data-name="'+title+'" id="insert_btn">@lang('filemanager.insert')</button>' +
            '   <button type="button" class="swal2-cancel swal2-styled font-size-14" aria-label="" style="display: inline-block;" id="cancel_footer_btn">@lang('filemanager.cancel')</button>' ;
        footer += ''+
            '</div>';
        var html = create_html(id,insert,title,user_name,created_date,updated_date,type,icon,src_original,src_large,src_medium,src_small,src_img,humman_size,humman_size_large,humman_size_medium,humman_size_small,public_original_path,public_large_path,public_medium_path,public_small_path) ;
        if (insert == 'insert' && type == 'Image' )
        {
            swal({
                html : html ,
                width: 850,
                imageWidth: 400,
                imageHeight: 300,
                showCancelButton: false,
                imageAlt: 'Custom image',
                animation: false,
                showConfirmButton: false,
                footer: footer,
            });
        }
        else
        {
            swal({
                html : html ,
                width: 850,
                imageWidth: 400,
                imageHeight: 300,
                showCancelButton: false,
                imageAlt: 'Custom image',
                animation: false,
                showConfirmButton: true,
                showCancelButton: true,
            });

        }



    });

    function init_fullscreen()
    {
        $('.demo-image').addClass('cursor_wait');
        setTimeout( function () {
            $('.demo-image').removeClass('cursor_wait');
            var elements = document.querySelectorAll( '.demo-image' );
            Intense( elements );
        },2000 ) ;
    }

    function create_html(id,insert,title,user_name,created_date,updated_date,type,icon,src_original,src_large,src_medium,src_small,src_img,humman_size,humman_size_large,humman_size_medium,humman_size_small,public_original_path,public_large_path,public_medium_path,public_small_path) {
        var parent_cat_name = $('#refresh_page').attr('data-category-name');
        var html =
            '' +
            '<div class="row">' +
            '<div class="demos col-md-6 sweet_icon">';
        if (type == "Image") {
            html += '<div data-title="' + title + '" data-caption="create at :' + created_date + ' by ' + user_name + '" class="demo-image first my_crop_image" data-image="' + src_original + '" >' +
                '<img data-id="'+id+'" src="' + src_img + '"  class="my_crop_image" onload="init_fullscreen()" >' +
                '</div>';
        }
        else if (type == "FileIcon") {
            html += '<i class="fa ' + icon + '" ></i>';
        }
        else {
            html += '<i class="fa fa-file" ></i>';
        }
        html +=
            '  </div>' +
            '<div class="col-md-6">' +
            '<h2>' + title + ' </h2>';

        if (type == "Image") {
            if (insert != 'insert') {
                html +=
                    '<div class="input-group margin-top-1">' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="original_link" target="_blank" class="color_white" href="'+src_original+'">@lang('filemanager.original')</a></span>' +
                    '   <input type="text" name="original_path" disabled class="form-control col-md-9" id="original" value="' + src_original + '">' +
                        @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="original_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_original" value="' + public_original_path + '">' +@endif
                    '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '   <button id="copy_path" class="btn btn-default" data-clipboard-target="original" ><i class="fa fa-copy"></i></button>' +
                    '   <span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                            '<div class="tooltip_copy input-group-append">' +
                            '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_original" ><i class="fa fa-copy"></i></button>' +
                            '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                            '</div>' ;
                    @endif
                    html +=
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="original_link" target="_blank" class="color_white" href="'+src_large+'">@lang('filemanager.large')</a></span>' +
                    '   <input type="text" name="large_path" disabled class="form-control col-md-9" id="large" value="' + src_large + '">' +
                            @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="large_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_large" value="' + public_large_path + '">' +@endif

                            '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_large+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '   <button id="copy_path" class="btn btn-default" data-clipboard-target="large" ><i class="fa fa-copy"></i></button>' +
                    '   <span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_large" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                     html +=
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="original_link" target="_blank" class="color_white" href="'+src_medium+'">@lang('filemanager.medium')</a></span>' +
                    '<input type="text" name="medium_path" disabled class="form-control col-md-9" id="medium" value="' + src_medium + '">' +
                     @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="medium_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_medium" value="' + public_medium_path + '">' +@endif
                     '<div class="input-group-append width_22">' +
                    '<span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_medium+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '<button id="copy_path" class="btn btn-default" data-clipboard-target="medium" ><i class="fa fa-copy"></i></button><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_medium" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                    html +=
                    '</div>' +
                    '<div class="input-group clearfix margin-top-1">' +
                    '<span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="original_link" target="_blank" class="color_white" href="'+src_small+'">@lang('filemanager.small')</a></span>' +
                    '<input type="text" name="small_path" disabled class="form-control col-md-9" id="small" value="' + src_small + '">' +
                            @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="small_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_small" value="' + public_small_path + '">' +@endif
                            '<div class="input-group-append width_22">' +
                    '<span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_small+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '<button id="copy_path" class="btn btn-default" data-clipboard-target="small" ><i class="fa fa-copy"></i></button><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                    html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_small" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif

                    html +=
                    '</div>' ;

                html +=
                    '<div class="detail_image clearfix">' +
                    '<div class="user_detail row">' +
                    '<i class="fa fa-calendar-plus-o col-md-6 col-sm-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                    '<i class="fa fa-user col-md-6 col-sm-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>'+
                    '<i class="fa fa-calendar-o col-md-6 col-sm-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>'+
                    '</div> ' +
                    '</div>' +
                    '</div>'+
                    '</div> ' +
                    '</div>' ;
            }
            else {
                html +=
                    '<div class="input-group margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio" name="selectimage" value="original">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="original_link" target="_blank" class="color_white" href="'+src_original+'">@lang('filemanager.original')</a></span>' +
                    '</div>' +
                    '   <input type="text" name="original_path" disabled class="form-control col-md-9" id="original" value="' + src_original + '">' +
                        @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="original_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_original" value="' + public_original_path + '">' +@endif
                        '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="original" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_original" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                    html +=
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="large">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="large_link" class="color_white" target="_blank" href="'+src_large+'">@lang('filemanager.large')</a></span>' +
                    '</div>' +
                    '<input type="text" name="large_path" disabled class="form-control col-md-9" id="large" value="' + src_large + '">' +
                            @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="large_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_large" value="' + public_large_path + '">' +@endif
                            '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_large+'</span>'+
                    '</div>' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="large" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_large" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                    html +=
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="medium">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="medium_link" class="color_white" href="'+src_medium+'" target="_blank">@lang('filemanager.medium')</a></span>' +
                    '</div>' +
                    '<input type="text" name="medium_path" disabled class="form-control col-md-9" id="medium" value="' + src_medium + '">' +
                            @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="medium_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_medium" value="' + public_medium_path + '">' +@endif
                            '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_medium+'</span>'+
                    '</div>' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="medium" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>' ;
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_medium" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                     html +=
                    '</div>' +
                    '</div>' +
                    '<div class="input-group clearfix margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="small">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="small_link" class="color_white" href="'+src_small+'" target="_blank">@lang('filemanager.small')</a></span>' +
                    '</div>' +
                    '<input type="text" name="small_path" disabled class="form-control col-md-9" id="small" value="' + src_small + '">' +
                            @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="small_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_small" value="' + public_small_path + '">' +@endif

                            '<div class="input-group-append width_22">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size_small+'</span>'+
                    '</div>' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="small" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                    '</div>';
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                        html +=
                        '<div class="tooltip_copy input-group-append">' +
                        '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_small" ><i class="fa fa-copy"></i></button>' +
                        '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                        '</div>' ;
                    @endif
                    html +=
                    '</div>' +
                    '</div>'
                ;
                html +=
                    '<div class="detail_image clearfix">' +
                    '<div class="user_detail row">' +
                    '<i class="fa fa-calendar-plus-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                    '<i class="fa fa-user col-md-6  margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>' +
                '<i class="fa fa-calendar-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>' ;

                html +=

                    '</div>'+
                    '</div>' +

                    '</div>' +
                    '</div>'+
                    '</div>' +

                    '</div>' ;
            }
        }
        else
        {
            html +=
                '<div class="input-group margin-top-1">' +
                '<span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="small_link" class="color_white" href="'+src_original+'" target="_blank">@lang('filemanager.original')</a></span>' +
                '<input type="text" name="original_path" disabled class="form-control col-md-9" id="original" value="' + src_original + '">' +
                    @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))'<input disabled type="text" name="original_public_path" data-type="public" class="form-control col-md-9 public_path_input" id="public_original" value="' + public_original_path + '">' +@endif
                    '<div class="input-group-append width_22">' +
                '   <span id="size" class="btn btn-default" data-clipboard-target="original" >'+humman_size+'</span>'+
                '</div>' +
                '<div class="tooltip_copy input-group-append">' +
                '<button id="copy_path" class="btn btn-default" data-clipboard-target="original" ><i class="fa fa-copy"></i></button><span class="tooltiptext" id="myTooltip">@lang('filemanager.click_to_copy')</span>' +
                '</div>' ;
                @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                    html +=
                    '<div class="tooltip_copy input-group-append">' +
                    '   <button id="copy_path" class="btn btn-default back_public" data-clipboard-target="public_original" ><i class="fa fa-copy"></i></button>' +
                    '   <span class="tooltiptext tootltip_public_path" id="myTooltip">@lang('filemanager.copy_public')</span>' +
                    '</div>' ;
                @endif
            html +=
                '</div>';
            html +=
                '<div class="detail_image clearfix">' +
                '<div class="user_detail row">' +
                '<i class="fa fa-calendar-plus-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                '<i class="fa fa-user col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>'+
                '<i class="fa fa-calendar-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>'+
                '</div> ' +
                '</div>' +
                '</div>'+
                '</div> ' +
                '</div>' ;
        }
        return html ;
    }
    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("keyup", '.change_dimention');
    $(document).on('keyup', '.change_dimention', function (e){
        var width = $('#change_width').val() ;
        var height = $('#change_height').val() ;
        var quality = $('#change_quality').val() ;
        var id = $(this).attr('data-id');
        set_path(id,width,height,quality)
    }) ;

    function set_path(id,width,height,quality) {
        $.each(['original','large','medium','small'],function (index,value) {
            url = download_url(id , value,'404.png',quality,width,height) ;
            $('#'+value).attr('value',url);
            $('#'+value+'_link').attr('href',url);
        });
    }

    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#cancel_footer_btn');
    $(document).on('click', '#cancel_footer_btn', function (e){
        swal.close();
    });
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#trashfile');
    $(document).on('click', '#trashfile', function (e) {
        e.preventDefault();
        swal({
            title: '@lang('filemanager.are_you_sure')',
            text: "@lang('filemanager.you_wont_be_able_to_revert_this')",
            cancelButtonText: '@lang('filemanager.no_cancel')',
            confirmButtonText: '@lang('filemanager.yes_delete_it')',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                var info = [] ;
                info['id'] = $(this).attr('data-id');
                info['type'] = $(this).attr('data-type');
                info['parent_id'] = $(this).attr('data-parent-id');
                trash(info);
                swal(
                    '@lang('filemanager.deleted')!',
                    '@lang('filemanager.your_file_has_been_deleted')'
                )
            }
        })
    });
    function trash(info) {
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.Trash')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: "json",
            data :{
                id:info['id'] ,
                type:info['type'],
                parent_id :info['parent_id'],
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
                callback: '{{LFM_CheckFalseString($callback)}}',
            },
            success: function (result) {
                var display = $( "#refresh_page" ).attr('data-type');

                if (result.success == true) {
                    $( ".panel-body" ).empty();
                    $( ".panel-body" ).html(result.html);
                    var type = $('#refresh_page').attr('data-type');
                    set_tab_show(type);
                    set_inserted_to_button(0);
                    set_jstree(result.allCategories,result.parent_category_id);
                }
            },
            error: function (e) {
            }
        });
    }

    function set_tab_show(type)
    {
        if(type == 'grid')
        {
            $('#show_grid_tab').tab('show') ;
            $('#refresh_page').attr('data-type','grid') ;
            $('#search_media').val('');
        }
        else if(type == 'search')
        {
            var value = $('#search_media').val() ;
            var id = {{$parent_id}};
            search(id,value) ;
        }
        else
        {
            $('#show_list_tab').tab('show') ;
            $('#refresh_page').attr('data-type','list') ;
            $('#search_media').val('');
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '.check');
    $(document).on('click', '.check', function (e) {
        $(this).toggleClass('selected');
        if($(this).hasClass('selected') == true && $(this).attr('data-type') == 'file')
        {
            var i = parseInt($('#insert_file').attr('data-value'));
            i = i+1 ;
            $(this).attr("checked", "checked");
            set_inserted_to_button(i);
        }
        else if($(this).hasClass('selected') == false && $(this).attr('data-type') == 'file')
        {
            var i = parseInt($('#insert_file').attr('data-value'));
            i = i-1 ;
            $(this).removeAttr('checked') ;
            set_inserted_to_button(i);
        }
    });
    function set_inserted_to_button(inserted_number)
    {
        if(inserted_number ==0)
        {
            $('#show_selected_item').empty() ;
            $('#show_selected_item').removeClass('border-doted-left-1') ;
        }
        else
        {
            $('#show_selected_item').addClass('border-doted-left-1') ;
            $('#show_selected_item').html('<span class="class="btn btn-default btn-sm">'+inserted_number+'</span>');
        }
        $('#insert_file').attr('data-value',inserted_number) ;


    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#bulk_delete');
    $(document).on('click', '#bulk_delete', function (e) {
        e.preventDefault();
        var type = $('refresh_page').attr('data-type');
        var items = get_selected(['file','category'],type) ;
        if (items.length > 0)
        {
            swal({
                title: '@lang('filemanager.are_you_sure')',
                text: "@lang('filemanager.you_wont_be_able_to_revert_this')",
                cancelButtonText: '@lang('filemanager.no_cancel')',
                confirmButtonText: '@lang('filemanager.yes_delete_it')',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                bulk_delete(items) ;
                swal(
                    '@lang('filemanager.deleted')!',
                    '@lang('filemanager.your_file_has_been_deleted')'
                    )
                }
                else if (result.dismiss === swal.DismissReason.cancel)
                {
                    swal(
                        '@lang('filemanager.cancelled')!',
                    )
                }
            })
        }

    });
    //------------------------------------------------------------------------------------------------------------------------------------------//


    function bulk_delete(items) {
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.BulkDelete')}}",
            dataType: "json",
            data :{
                items:items ,
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
                callback: '{{LFM_CheckFalseString($callback)}}',
            },
            success: function (result) {
                var display = $( "#refresh_page" ).attr('data-type');
                var id = $( "#refresh_page" ).attr('data-id');
                var type = $('#refresh_page').attr('data-type');
                if (result.success == true) {
                    $( ".panel-body" ).empty();
                    $( ".panel-body" ).html(result.html);
                    set_tab_show(type);
                    set_inserted_to_button(0);
                    set_jstree(result.allCategories,result.parent_category_id);
                }
            },
            error: function (e) {
            }
        });
    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#CopyoriginalPath');
    $(document).on('click', '#CopyoriginalPath', function (e) {
        value = $(this).attr('data-original');
        setClipboard(value);
    });
    function setClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#copy_path');
    $(document).on('click', '#copy_path', function (e) {
        var target =$(this).attr('data-clipboard-target');
        $('#'+target).removeAttr('disabled');
        var copyText = document.getElementById(target);
        copyText.select();
        document.execCommand("Copy");
        $(this).next('span#myTooltip').html('@lang('filemanager.copied')') ;
        $(this).mouseout(function () {
            $(this).next('span#myTooltip').html('@lang('filemanager.click_to_copy')') ;
        });
        $('#'+target).attr('disabled',true);
    });

    //-----------------------------------------------------------------------------------------------------------------------//
    function show_category(id) {
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.ShowCategory')}}",
            data: {
                category_id: id ,
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
                callback: '{{LFM_CheckFalseString($callback)}}',
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (result) {
                var type = $('#refresh_page').attr('data-type');
                if (result.success == true)
                {
                    $( ".panel-body" ).empty();
                    $( ".uploadfile" ).attr('href' , result.button_upload_link);
                    $( ".create_category" ).attr('href' , result.button_category_create_link);
                    $( "#refresh_page" ).attr('data-id' , result.parent_category_id);
                    $( "#refresh_page" ).attr('data-category-name' , result.parent_category_name);
                    $( ".panel-body" ).html(result.html);
                    set_tab_show(type);
                    set_inserted_to_button(0);//set no inserted
                    set_jstree(result.allCategories,result.parent_category_id);
                }
            },
            error: function (e) {
            }
        });
    }

    function getAttributes() {
        var attrs = {};
        $.each($node[0].attributes, function (index, attribute) {
            attrs[attribute.name] = attribute.value;
        });
        return attrs;
    }

    function get_selected(data_type,data_view,insert)
    {
        data_type = data_type || false ;
        data_view = data_view || 'grid' ;
        insert = insert || false ;
        var items = [];
        $('.selected').each(function(k , v) {
            if(data_type && data_view)
            {
                var data_t = $(this).attr('data-type');
                var data_v = $(this).attr('data-view');
                if($.inArray(data_t,data_type) !=-1 && data_v==data_view)
                {
                    var $this = $(this);
                    if (!insert)
                    {
                        item = {
                            'id' :$this.data('id') ,
                            'type' : data_t,
                            'parent_id' : $this.data('parent-id') ,
                        }
                    }
                    else
                    {
                        item = {
                            'id' : $this.data('id') ,
                            'type' : 'original' ,
                            'parent_id' : $this.data('parent-id') ,
                            'name' :  $this.data('name'),
                            'width' : 0,
                            'height' : 0,
                            'quality':100,
                        }
                    }
                    items.push(item) ;
                }
            }
            else
            {
                var $this = $(this);
                item = {
                    'id' : $this.data('id') ,
                    'type' : $this.data('type') ,
                    'parent_id' : $this.data('parent-id') ,

                }
                items.push(item) ;
            }
        });
        return items
    }

    function set_selected_all()
    {
        $('.check').each(function () {
            $(this).addClass('selected');
            $(this).prop('checked', true);
        }) ;
    }

</script>
