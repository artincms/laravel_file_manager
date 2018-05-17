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
        $('.media-content').append(generate_loader_html('لطفا منتظر بمانید...'));
        var id = $(this).attr('data-id');
        show_category(id);
    });

    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#select_all');
    $(document).on('click', '#select_all', function (e) {
        $('.check').each(function () {
            $(this).addClass('selected');
            $(this).attr("checked", "checked");
        }) ;
        $('.toggle_select').attr("id", "select_none");
    });
    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#select_none');
    $(document).on('click', '#select_none', function (e) {
        $('.check').each(function () {
            $(this).removeClass('selected');
            $(this).removeAttr("checked");
        });
        $('.toggle_select').attr("id","select_all")
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
        var src_orginal = download_url(id,'orginal');
        var src_large = download_url(id,'large');
        var src_medium = download_url(id,'medium');
        var src_small = download_url(id,'small');
        var src_img = download_url(id,'small','404.png',100,250,200);
        var insert = '{{$insert}}'  ;
        var humman_size = $(this).attr('data-humman_size');
        var footer = '' +
            '<div class="swal2-actions" style="display: flex;">' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100" id="basic-addon1">width</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="Width" aria-label="width" name="width_picture" aria-describedby="basic-addon1" value="0" id="change_width">' +
            '</div>' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100" id="basic-addon1">Height</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="Height" aria-label="height" name="height_picture" aria-describedby="basic-addon1" value="0" id="change_height">' +
            '</div>' +
            '<div class="input-group dimension margin-top-1">' +
            '  <div class="input-group-prepend">' +
            '    <span class="input-group-text width-100" id="basic-addon1">Quality</span>' +
            '  </div>' +
            '  <input data-id="'+id+'" type="text" class="form-control change_dimention" placeholder="quality" aria-label="quality" name="quality_picture" aria-describedby="basic-addon1" value="100" id="change_quality">' +
            '</div>' +
            '   <button type="button" class="swal2-confirm btn pull-right swal2-styled font-size-14" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);" data-id="'+id+'" data-name="'+title+'" id="insert_btn">insert</button>' +
            '   <button type="button" class="swal2-cancel swal2-styled font-size-14" aria-label="" style="display: inline-block;" id="cancel_footer_btn">Cancel</button>' ;
        footer += ''+
            '</div>';
        var html = create_html(id,insert,title,user_name,created_date,updated_date,type,icon,src_orginal,src_large,src_medium,src_small,src_img,humman_size) ;
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
        },1000 ) ;

    }

    function create_html(id,insert,title,user_name,created_date,updated_date,type,icon,src_orginal,src_large,src_medium,src_small,src_img,humman_size) {
        var html =
            '' +
            '<div class="row">' +
            '<div class="demos col-md-6 sweet_icon">';
        if (type == "Image") {
            html += '<div data-title="' + title + '" data-caption="create at :' + created_date + ' by ' + user_name + '" class="demo-image first my_crop_image" data-image="' + src_orginal + '" >' +
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
                    '   <span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="orginal_link" target="_blank" class="color_white" href="'+src_orginal+'">Orginal</a></span>' +
                    '   <input type="text" name="orginal_path" disabled class="form-control col-md-9" id="orginal" value="' + src_orginal + '">' +
                    '<div input-group-append">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="orginal" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '   <button id="copy_path" class="btn btn-default" data-clipboard-target="orginal" ><i class="fa fa-copy"></i></button>' +
                    '   <span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="orginal_link" target="_blank" class="color_white" href="'+src_large+'">Large</a></span>' +
                    '   <input type="text" name="large_path" disabled class="form-control col-md-9" id="large" value="' + src_large + '">' +
                    '<div input-group-append">' +
                    '   <span id="size" class="btn btn-default" data-clipboard-target="orginal" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '   <button id="copy_path" class="btn btn-default" data-clipboard-target="large" ><i class="fa fa-copy"></i></button>' +
                    '   <span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="orginal_link" target="_blank" class="color_white" href="'+src_medium+'">Medium</a></span>' +
                    '<input type="text" name="medium_path" disabled class="form-control col-md-9" id="medium" value="' + src_medium + '">' +
                    '<div input-group-append">' +
                    '<span id="size" class="btn btn-default" data-clipboard-target="orginal" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '<button id="copy_path" class="btn btn-default" data-clipboard-target="medium" ><i class="fa fa-copy"></i></button><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group clearfix margin-top-1">' +
                    '<span class="input-group-addon btn-primary color_white" id="basic-addon1"><a id="orginal_link" target="_blank" class="color_white" href="'+src_small+'">Small</a></span>' +
                    '<input type="text" name="small_path" disabled class="form-control col-md-9" id="small" value="' + src_small + '">' +
                    '<div input-group-append">' +
                    '<span id="size" class="btn btn-default" data-clipboard-target="orginal" >'+humman_size+'</span>'+
                    '</div>' +
                    '<div class="tooltip_copy input-group-append">' +
                    '<button id="copy_path" class="btn btn-default" data-clipboard-target="small" ><i class="fa fa-copy"></i></button><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' ;

                html +=
                    '<div class="detail_image clearfix">' +
                    '<div class="user_detail row">' +
                    '<i class="fa fa-calendar-plus-o col-md-6 col-sm-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                    '<i class="fa fa-calendar-o col-md-6 col-md-6 col-sm-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>'+
                    '<i class="fa fa-user col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>'+

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
                    '      <input type="radio" name="selectimage" value="orginal">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="orginal_link" target="_blank" class="color_white" href="'+src_orginal+'">Orginal</a></span>' +
                    '</div>' +
                    '   <input type="text" name="orginal_path" disabled class="form-control col-md-9" id="orginal" value="' + src_orginal + '">' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="orginal" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="large">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="large_link" class="color_white" target="_blank" href="'+src_large+'">large</a></span>' +
                    '</div>' +
                    '<input type="text" name="large_path" disabled class="form-control col-md-9" id="large" value="' + src_large + '">' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="large" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="medium">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="medium_link" class="color_white" href="'+src_medium+'" target="_blank">Medium</a></span>' +
                    '</div>' +
                    '<input type="text" name="medium_path" disabled class="form-control col-md-9" id="medium" value="' + src_medium + '">' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="medium" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group clearfix margin-top-1">' +
                    '<div class="input-group-prepend width-30">' +
                    '    <div class="input-group-text">' +
                    '      <input type="radio"  name="selectimage" value="small">' +
                    '    </div>' +
                    '   <span class="input-group-addon btn-primary color_white" id="basic-insert"><a id="small_link" class="color_white" href="'+src_small+'" target="_blank">Small</a></span>' +
                    '</div>' +
                    '<input type="text" name="small_path" disabled class="form-control col-md-9" id="small" value="' + src_small + '">' +
                    '<div class="input-group-append"> ' +
                    '<div class="tooltip_copy btn btn-outline-secondary">' +
                    '<span id="copy_path" data-clipboard-target="small" ><i class="fa fa-copy"></i></span><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                ;
                html +=

                    '<div class="detail_image clearfix">' +
                    '<div class="user_detail row">' +
                    '<i class="fa fa-calendar-plus-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                    '<i class="fa fa-calendar-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>'+
                    '<i class="fa fa-user col-md-12 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>';
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
                '<span class="input-group-addon btn-primary color_white" id="basic-addon1">Orginal</span>' +
                '<input type="text" name="orginal_path" disabled class="form-control col-md-9" id="orginal" value="' + src_orginal + '">' +
                '<div class="tooltip_copy input-group-append">' +
                '<button id="copy_path" class="btn btn-default" data-clipboard-target="orginal" >copy</button><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                '</div>' +
                '</div>';
            html +=
                '<div class="detail_image clearfix">' +
                '<div class="user_detail row">' +
                '<i class="fa fa-calendar-plus-o col-md-6 6" aria-hidden="true"><span class="icon_info_image">'+created_date+'</span></i>'+
                '<i class="fa fa-calendar-o col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+updated_date+'</span></i>'+
                '<i class="fa fa-user col-md-6 margin-top-1" aria-hidden="true"><span class="icon_info_image">'+user_name+'<span></i>'+
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
        $.each(['orginal','large','medium','small'],function (index,value) {
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
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                var info = [] ;
                info['id'] = $(this).attr('data-id');
                info['type'] = $(this).attr('data-type');
                info['parent_id'] = $(this).attr('data-parent-id');
                trash(info);
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    });
    function trash(info) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.Trash')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: "json",
            data :{
                id:info['id'] ,
                type:info['type'],
                parent_id :info['parent_id'],
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
            },
            success: function (result) {
                var display = $( "#refresh_page" ).attr('data-type');

                if (result.success == true) {
                    $( ".panel-body" ).empty();
                    $( ".panel-body" ).html(result.html);
                    var type = $('#refresh_page').attr('data-type');
                    if(type == 'grid')
                    {
                        $('#show_grid_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','grid') ;
                    }
                    else
                    {
                        $('#show_list_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','list') ;
                    }
                }
            },
            error: function (e) {
            }
        });
    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '.check');
    $(document).on('click', '.check', function (e) {
        $(this).toggleClass('selected');
        if($(this).hasClass('selected') == true && $(this).attr('data-type') == 'file')
        {
            var i = parseInt($('#insert_file').attr('data-value'));
            i = i+1 ;
            $('#insert_file').attr('data-value',i) ;
            $(this).attr("checked", "checked");
            $('#show_selected_item').addClass('border-doted-left-1') ;
            $('#show_selected_item').html('<span class="class="btn btn-default btn-sm">'+i+'</span>');
        }
        else if($(this).hasClass('selected') == false && $(this).attr('data-type') == 'file')
        {
            var i = parseInt($('#insert_file').attr('data-value'));
            i = i-1 ;
            $('#insert_file').attr('data-value',i) ;
            $('#show_selected_item').html('<span class="fa-stack fa-1x btn btn-default btn-sm selected_insert"><i class="fa fa-circle-o fa-stack-2x"></i><strong class="fa-stack-1x">'+i+'</strong></span>');

        }
    });
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#bulk_delete');
    $(document).on('click', '#bulk_delete', function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                items = get_selected() ;
                bulk_delete(items) ;
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
            else if (result.dismiss === swal.DismissReason.cancel)
            {
                swal(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        })
    });
    //------------------------------------------------------------------------------------------------------------------------------------------//

    function get_selected(data_type,width,height,type,quality)
    {
        var width = width || 0 ;
        var height = height || 0 ;
        var type = type || 'orginal' ;
        var quality = quality || 100 ;
        data_type = data_type || false ;
        var items = [];
        $('.selected').each(function(k , v) {
            if(data_type !=false)
            {
                var data_t = $(this).attr('data-type')
                if(data_t == data_type)
                {
                    var $this = $(this);
                    item = {
                        'id' : $this.data('id') ,
                        'type' : $this.data('type') ,
                        'parent_id' : $this.data('parent-id') ,
                        'name' :  $this.data('name'),
                        'width' : width,
                        'height' : height,
                        'type' : type,
                        'quality':quality

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
    function bulk_delete(items) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.BulkDelete')}}",
            dataType: "json",
            data :{
                items:items ,
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
            },
            success: function (result) {
                var display = $( "#refresh_page" ).attr('data-type');
                var id = $( "#refresh_page" ).attr('data-id');
                var type = $('#refresh_page').attr('data-type');
                if (result.success == true) {
                    $( ".panel-body" ).empty();
                    $( ".panel-body" ).html(result.html);


                    if(type == 'grid')
                    {
                        $('#show_grid_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','grid') ;
                    }
                    else
                    {
                        $('#show_list_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','list') ;
                    }
                }
            },
            error: function (e) {
            }
        });
    }
    //-----------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#CopyOrginalPath');
    $(document).on('click', '#CopyOrginalPath', function (e) {
        value = $(this).attr('data-orginal');
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
        $(this).next('span#myTooltip').html('Copied') ;
        $(this).mouseout(function () {
            $(this).next('span#myTooltip').html('click to copy') ;
        });
        $('#'+target).attr('disabled',true);
    });

    //-----------------------------------------------------------------------------------------------------------------------//

    function show_category(id) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.ShowCategory')}}",
            data: {
                category_id: id ,
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
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
                    if(type == 'grid')
                    {
                        $('#show_grid_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','grid') ;
                    }
                    else
                    {
                        $('#show_list_tab').tab('show') ;
                        $('#refresh_page').attr('data-type','list') ;
                    }
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

</script>
