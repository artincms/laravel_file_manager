<script type="text/javascript">

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
    });

    function set_jstree(jdata,parent_category_id)
    {
        console.log(parent_category_id);
        $('#js_tree_share_div').html('<div id="jstree_category_share"></div>') ;
        $('#js_tree_public_div').html('<div id="jstree_category_public"></div>') ;
        $('#js_tree_root_div').html('<div id="jstree_category_root"></div>') ;
        if(parent_category_id ==-2)
        {
            $('#share_category').addClass('jstree-root');
            $('#public_category').removeClass('jstree-root');
            $('#media_category').removeClass('jstree-root');
        }
        else if(parent_category_id ==-1)
        {
            $('#public_category').addClass('jstree-root');
            $('#share_category').removeClass('jstree-root');
            $('#media_category').removeClass('jstree-root');
        }
        else if(parent_category_id ==0)
        {
            $('#media_category').addClass('jstree-root');
            $('#public_category').removeClass('jstree-root');
            $('#share_category').removeClass('jstree-root');
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
            console.log('d');
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
        var iframe = $('iframe.modal_iframe');
        iframe.attr("src",src);

    });
    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#EditFile');
    $(document).on('click', '#EditFile', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        $('iframe.modal_iframe').attr("src",src);
        $('.modal-footer').addClass('hidden') ;

    });
    //---------------------------------------------------------------------------------------------------------------------//
    $(document).off("click", '#EditFileName');
    $(document).on('click', '#EditFileName', function (e) {
        e.preventDefault() ;
        var src = $(this).attr('href') ;
        $('iframe.modal_iframe').attr("src",src);
        $('.modal-footer').addClass('hidden') ;

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
        var id = 0;
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
        var datas=[];
        datas = create_insert_data(items) ;
        @if($callback)
        if (typeof parent.{{LFM_CheckFalseString($callback)}} !== 'undefined')
        {
            parent.{{LFM_CheckFalseString($callback)}}(datas) ;
        }
        @endif
        clear_page() ;
        if(typeof parent.hidemodal !== 'undefined')
        {
            parent.hidemodal();
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
                res = result ;
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
    var panel_height = $('.panel-body').height();

</script>
