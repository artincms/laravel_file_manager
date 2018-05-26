<script type="text/javascript">

    //---------------------------------------------------------------------------------------------------------------------//
    //Edit Picture



    //---------------------------------------------------------------------------------------------------------------------//
    //js tree
    $( document ).ready(function() {
        var jdata = {!! $allCategories !!};
        $('#jstree_category_share').jstree(
            {
                'core' : {
                    'data' : jdata.share
                }
            });
        $('#jstree_category_public').jstree(
            {
                'core' : {
                    'data' : jdata.public
                }
            });
        $('#jstree_category_root').jstree(
            {
                'core' : {
                    'data' : jdata.root
                }
            });
        $('#media_category').addClass('jstree-root');
    });

    function set_jstree(jdata,parent_category_id)
    {
        $('#js_tree_share_div').html('<div id="jstree_category_share"></div>') ;
        $('#js_tree_public_div').html('<div id="jstree_category_public"></div>') ;
        $('#js_tree_root_div').html('<div id="jstree_category_root"></div>') ;
        if(parent_category_id ==-2)
        {
            $('#share_category').addClass('jstree-root');
            $('#public_category').removeClass('jstree-root');
            $('#media_category').removeClass('jstree-root');
            $('#share_category i').removeClass('fa-folder').addClass('fa-folder-open');
            $('#public_category i').removeClass('fa-folder-open').addClass('fa-folder');
            $('#media_category i').removeClass('fa-folder-open').addClass('fa-folder');

        }
        else if(parent_category_id ==-1)
        {
            $('#public_category').addClass('jstree-root');
            $('#share_category').removeClass('jstree-root');
            $('#media_category').removeClass('jstree-root');
            $('#public_category i').removeClass('fa-folder').addClass('fa-folder-open');
            $('#share_category i').removeClass('fa-folder-open').addClass('fa-folder');
            $('#media_category i').removeClass('fa-folder-open').addClass('fa-folder');

        }
        else if(parent_category_id ==0)
        {
            $('#media_category').addClass('jstree-root');
            $('#public_category').removeClass('jstree-root');
            $('#share_category').removeClass('jstree-root');
            $('#media_category i').removeClass('fa-folder').addClass('fa-folder-open');
            $('#public_category i').removeClass('fa-folder-open').addClass('fa-folder');
            $('#share_category i').removeClass('fa-folder-open').addClass('fa-folder');

        }
        else
        {
            $('#media_category').removeClass('jstree-root');
            $('#public_category').removeClass('jstree-root');
            $('#share_category').removeClass('jstree-root');
        }

        $('#jstree_category_share').jstree(
            {
                'core' : {
                    'data' : jdata.share
                }
            });
        $('#jstree_category_public').jstree(
            {
                'core' : {
                    'data' : jdata.public
                }
            });
        $('#jstree_category_root').jstree(
            {
                'core' : {
                    'data' : jdata.root
                }
            });
    }
    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#root_category');
    $(document).on('click', '#root_category', function (e){
        $('#root_category').each(function () {
            /*$(this).removeClass('jstree-root');*/
        });
        $(this).addClass('jstree-root');
    });

    //---------------------------------------------------------------------------------------------------------------------//
    //show shares folder
    $(document).off("click", '#top_share_folder');
    $(document).on('click', '#top_share_folder', function (e) {
        $('#show_share_tab').tab('show') ;
    });

    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#EditCategory');
    $(document).on('click', '#EditCategory', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        var iframe = $('#modal_iframe_edit_category');
        var title=$(this).attr('data-category-name');
        $('h5.title_edit_category').html('Edit Category '+title);
        iframe.attr("src",src);
        iframe.on("load", function() {
            $(document).off('click','#create_edit_category_modal_button');
            $(document).on('click','#create_edit_category_modal_button', function (e) {
                var selector = iframe.contents().find("#btn_submit_edit_category");
                selector.click();
            });
            iframe.on("load", function() {
                $(document).off('click','#create_edit_category_modal_button_close');
                $(document).on('click','#create_edit_category_modal_button_close', function (e) {
                    var selector = iframe.contents().find("#btn_submit_edit_category_close");
                    selector.click();
                });
            });
        });
    });
    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#EditFile');
    $(document).on('click', '#EditFile', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        var iframe = $('#modal_iframe_edit_picture');
        iframe.attr("src",src);
        iframe.on("load", function() {
            //---------------------------show orginal----------------------------------------------//
            $(document).off('click', '#nva_orginal');
            $(document).on('click', '#nva_orginal', function (e) {
                if (typeof (iframe[0].contentWindow.show_crop_orginal) == "function")
                    iframe[0].contentWindow.show_crop_orginal();
                else
                    alert("resultFrame.show_crop_orginal NOT found");
                $('#nva_orginal').tab('show');
                $('#create_edit_picture_modal_button').attr('data-type','orginal') ;
            });

            //---------------------------show large crop----------------------------------------------//
            $(document).off('click', '#nva_large');
            $(document).on('click', '#nva_large', function (e) {
                if (typeof (iframe[0].contentWindow.show_crop_large) == "function")
                    iframe[0].contentWindow.show_crop_large();
                else
                    alert("resultFrame.show_crop_large NOT found");
                $('#nva_large').tab('show');
                $('#create_edit_picture_modal_button').attr('data-type','large') ;
            });

            //---------------------------show medium crop----------------------------------------------//
            $(document).off('click', '#nva_medium');
            $(document).on('click', '#nva_medium', function (e) {
                if (typeof (iframe[0].contentWindow.show_crop_medium) == "function")
                    iframe[0].contentWindow.show_crop_medium();
                else
                    alert("resultFrame.show_crop_medium NOT found");
                $('#nva_medium').tab('show');
                $('#create_edit_picture_modal_button').attr('data-type','medium') ;

            });

            //---------------------------show small crop----------------------------------------------//
            $(document).off('click', '#nva_small');
            $(document).on('click', '#nva_small', function (e) {
                if (typeof (iframe[0].contentWindow.show_crop_small) == "function")
                    iframe[0].contentWindow.show_crop_small();
                else
                    alert("resultFrame.show_crop)small NOT found");
                $('#nva_small').tab('show');
                $('#create_edit_picture_modal_button').attr('data-type','small') ;
            });

            $(document).off('click', '#create_edit_picture_modal_button');
            $(document).on('click', '#create_edit_picture_modal_button', function (e) {
                var type = $(this).attr('data-type');
                var selector = iframe.contents().find('#crope_button_'+type);
                console.log(selector,'#crop_button_'+type);
                selector.click();
            });

            $("#create_edit_picture_modal").on("hidden.bs.modal", function () {
                $('#nva_orginal').tab('show');
                $('#create_edit_picture_modal_button').attr('data-type','orginal') ;
            });
        });

    });
    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#EditFileName');
    $(document).on('click', '#EditFileName', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        var title =$().attr('data-file-name');
        var iframe = $('#modal_iframe_edit_file_name');
        $('.title_edit_file_name').html('Edit file ' +title);
        iframe.attr("src",src);
        iframe.on("load", function() {
            $(document).off('click','#create_edit_file_name_modal_button');
            $(document).on('click','#create_edit_file_name_modal_button', function (e) {
                var selector = iframe.contents().find("#btn_submit_update_file_name");
                selector.click();
            });
            $(document).off('click','#create_edit_file_name_modal_button_close');
            $(document).on('click','#create_edit_file_name_modal_button_close', function (e) {
                var selector = iframe.contents().find("#btn_submit_update_file_name_close");
                selector.click();
            });
        });
    });

    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '.create_category');
    $(document).on('click', '.create_category', function (e) {
        var src = $(this).attr('href') ;
        var iframe = $('#modal_iframe_category');
        iframe.attr("src",src);
        iframe.on("load", function() {
            $(document).off('click','#create_category_modal_button');
            $(document).on('click','#create_category_modal_button', function (e) {
                var selector = iframe.contents().find("#btn_submit_category");
                selector.click();
            });
            $(document).off('click','#create_category_modal_button_close');
            $(document).on('click','#create_category_modal_button_close', function (e) {
                var selector = iframe.contents().find("#btn_submit_category_close");
                selector.click();
            });
        });
    });


    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '.uploadfile');
    $(document).on('click', '.uploadfile', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        $('iframe.modal_iframe').attr("src",src);
    });

    //---------------------------------------------------------------------------------------------------------------------//
    //show grid page
    $(document).off("click", '#refresh_page');
    $(document).on('click', '#refresh_page', function (e) {
        e.preventDefault();
        $('.media-content').append(generate_loader_html('لطفا منتظر بمانید...'));
        refresh() ;

    });

    function refresh() {
        var id = $('#refresh_page').attr('data-id');
        show_category(id);
    }

    //---------------------------------------------------------------------------------------------------------------------//

    $(document).off("click", '#show_grid');
    $(document).on('click', '#show_grid', function (e) {
        id =$('#refresh_page').attr('data-id') ;
        $('#refresh_page').attr('data-type','grid') ;
        $('#show_grid_tab').tab('show') ;
        //show_category(id);
    });

    //---------------------------------------------------------------------------------------------------------------------//
    //search in category and children
    $(document).off("click", '#search-btn');
    $(document).on('click', '#search-btn', function (e) {
        e.preventDefault() ;
        var target = $(this).attr('data-target-search') ;
        var value = $('#'+target).val() ;
        var id = {{$parent_id}};
        if(value !='')
        {
            search(id,value) ;
        }
    });

    function search(id,search)
    {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.SearchMedia')}}",
            dataType: "json",
            data :{
                section:'{{LFM_CheckFalseString($section)}}',
                insert: '{{LFM_CheckFalseString($insert)}}',
                id:id,
                search:search
            },
            success: function (result) {
                if (result.success == true)
                {
                    $( "#show_search_result" ).html(result.html);
                    $('#show_search_tab').tab('show') ;
                }
            },
            error: function (e) {
            }
        })
    }

    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#insert_file');
    $(document).on('click', '#insert_file', function (e) {
        var type = $('refresh_page').attr('data-type');
        var items = get_selected(['file'],type,true);
        var available = {!! $available !!} ;
        console.log(available);
        var datas=[];
        if ( available !='undefined' && available >= items.length)
        {
            datas = create_insert_data(items) ;
        }
        else
        {
            swal({
                type: 'error',
                title: 'you cant inserted',
                text: 'you cant inserted more than '+available +' file' ,
            });
        }
    });

    function clear_page() {
        $('.selected').each(function () {
            $(this).removeClass('selected');
            $(this).prop("checked",false);
            $('#insert_file').attr('data-value' ,0);
            $('#show_selected_item').empty();
            $('#show_selected_item').removeClass('border-doted-left-1');
            $('.total_loader').remove() ;

        });
    }
    //---------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#insert_btn');
    $(document).on('click', '#insert_btn', function (e){
        var data = {} ;
        var id = $(this).attr('data-id');
        var width = $('#change_width').val() || 0;
        var height  = $('#change_height').val() || 0 ;
        var quality = $('#change_quality').val() || 100;
        var type  = $('input[name=selectimage]:checked').val() || 'orginal' ;
        var datas = [{id:id , width : width , height:height , quality : quality , type : type}] ;
        var result = create_insert_data(datas) ;
        $('#cancel_footer_btn').click();
            @if ($callback)
        {
            parent.{{LFM_CheckFalseString($callback)}}(result) ;
        }
        @endif

    });
    function create_insert_data(value)
    {
        var res ='';
        $.ajax({
            type: "POST",
            url: "{{route('LFM.CreateInsertData')}}",
            dataType: "json",
            async: false,
            data :{
                items:value,
                section : '{{LFM_CheckFalseString($section)}}'

            },
            success: function (result) {
                console.log(result);
                if (result.{{LFM_CheckFalseString($section)}}.success)
                {
                    res = result ;
                    parent.{{LFM_CheckFalseString($section)}}_available = result.{{LFM_CheckFalseString($section)}}.available ;
                    clear_page() ;
                    @if($callback)
                    if (typeof parent.{{LFM_CheckFalseString($callback)}} !== 'undefined')
                    {
                        parent.{{LFM_CheckFalseString($callback)}}(result) ;
                    }
                    @endif
                    if(typeof parent.hidemodal !== 'undefined')
                    {
                        parent.hidemodal();
                    }

                }
                else
                {
                    var res = false ;
                    swal({
                        type: 'error',
                        title: result.{{LFM_CheckFalseString($section)}}.error,
                    });
                }

            },
            error: function (e) {
                res =  false ;
            }
        });
        return res ;
    }

    //-----------------------------------------------------------------------------------------------------------------------------//
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

    function download_url(id , type,img,quality,width,height) {
        var typ_file = type || 'orginal' ;
        var default_img = img || '404.png' ;
        var quality_file = quality || 90 ;
        var width_file = width || 0 ;
        var height_file = height || 0 ;

        var url = '/LFM/DownloadFile/ID/'+id+'/'+typ_file+'/'+default_img+'/'+quality_file+'/'+width_file+'/'+height_file ;
        return url ;
    }
    //-------------------------------------------------------------------------------------------------------------------------//
    $('.close_upload').off('click');
    $('.close_upload').on('click', function () {
        ('#create_upload_modal').modal('hide');
    });
</script>
