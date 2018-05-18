<script type="text/javascript">


//----------------------------------------------------------------------------------//
$(document).off("click", '#category_list');
$(document).on('click', '#category_list', function (e) {
    e.preventDefault() ;
    var category_id = $(this).attr('data-id');
    var cat_name = $(this).attr('data-name');
    datatable(category_id, cat_name);
});

function breadcrumbs(id, cat_name) {
    cat_name = cat_name || false ;
    var bread = ''
    $.ajax({
        type: "POST",
        url: '/LFM/Breadcrumbs/' + id,
        dataType: "json",
        success: function (result) {
            bread += '<nav aria-label="breadcrumb"> ' +
                '<ol class="breadcrumb">';
            $.each(result, function (key, value) {
                bread += '<li class="breadcrumb-item"><a href="#" data-id="' + value.id + '" id="category_list" data-name="'+value.title+'">' + value.title + '</a></li>';
            });
            if (cat_name != "media" && cat_name != false) {
                bread += '<li class="breadcrumb-item">' + cat_name + '</li>';
            }
            bread += '</ol>' +
                '</nav>';

            $('.panel-body').prepend(bread);
        },
        error: function (e) {
        }
    });
    return bread;
}

function datatable(id, cat_name) {
    var cat_name = cat_name || false;
    var section = $('#refresh_page').attr('data-section');
    var ajax_url = '/LFM/ShowListCategory';
    var more_data = {
        id: id,
        section : section
    };
    var columns = get_columns() ;
    $('#grid_media_manager').dataTable({
        searching: false,
        serverSide: false,
        ajax: {
            url: ajax_url,
            type: 'POST',
            data: more_data
        },
        columns: columns,
    });
   set_attribute(id,cat_name);
}

function datatable_search(id, search ,cat_name) {
    var cat_name = cat_name || false;
    var search = search || false ;
    var ajax_url = '/LFM/SearchMedia';
    var more_data = {
        id: id,
        search: search
    };
    var columns = get_columns() ;
    columns.push(add_path_column());
    console.log(columns);
    $('#search_media_datatable').dataTable({
        searching: false,
        serverSide: false,
        ajax: {
            url: ajax_url,
            type: 'POST',
            data: more_data
        },
        columns: columns,
    });
    set_attribute(id,cat_name);
}
function get_columns() {
    var picture_mimetype = ['image/jpeg', 'image/png'];
    var columns = [
        {
            title: '<input name="select_all" id="select_all" value="1" type="checkbox" class="check toggle_select"/>',
            searchable: false,
            orderable: false,
            width: '1%',
            className: 'dt-body-center',
            render: function (data, type, full, meta) {
                return '<input type="checkbox" class="check" data-type="' + full.type + '" data-id="' + full.id + '" data-name="'+full.name+'" data-parent-id="' + full.category_id + '">';
            }
        },
        {
            title: "Row",
            data: "id",
            sortable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            title: "ID",
            data: "id",
            name: "id"
        },
        {
            title: "Name",
            data: 'name',
            name: 'name',
            mRender: function (data, type, row) {
                if (row.user == null) {
                    row.user = {};
                    row.user.name = 'public';
                }

                if (row.type == 'category') {
                    return '<a data-id="' + row.id + '" id="category_list" data-name="' + row.name + '"><i class="fa ' + row.icon + '"><span class="list_name_icon">' + row.name + '</span></a>';
                }
                else if ($.inArray(row.mimeType, picture_mimetype) != '-1') {
                    return '<a id="sweet_image" data-id="' + row.id + '" id="file_list" data-type="Image" title="' + row.name + '" data-user-name ="' + row.user.name + '" data-created-date="' + row.created_at + '" data-updated-date="' + row.updated_at + '"><i class="fa ' + row.icon + '"><span class="list_name_icon">' + row.name + '</span></a>';


                }
                else {
                    return '<a id="sweet_image" data-id="' + row.id + '" id="file_list" data-type="File" title="' + row.name + '" data-user-name ="' + row.user.name + '" data-created-date="' + row.created_at + '" data-updated-date="' + row.updated_at + '"><i class="fa ' + row.icon + '"><span class="list_name_icon">' + row.name + '</span></a>';

                }
            }
        },
        {
            title: "User",
            data: 'user',
            name: 'user',
            mRender: function (data, type, row) {
                return row.user.name;

            }
        },
        {
            title: "Action",
            searchable: false,
            orderable: false,
            mRender: function (data, type, row) {
                var html = '';
                if (row.parent_category == null) {
                    parent_id = 0;
                }
                else {
                    parent_id = row.parent_category;
                }
                if (row.type == 'category') {
                    html += '<a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="' + row.type + '" data-id = "' + row.id + '" data-parent-id="' + parent_id + '" >' +
                        '<i class="fa fa-trash"></i>' +
                        '</a>';
                }
                else if ($.inArray(row.mimeType, picture_mimetype) != '-1') {
                    html += '<a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="' + row.type + '" data-id = "' + row.id + '" data-parent-id="' + row.category_id + '" >' +
                        '<i class="fa fa-trash"></i>' +
                        '</a>';
                    html += '' +
                        '<a href="/LFM/EditPicture/' + row.id + '" class="grid-row-edit pull-right myicon" id="EditFile" data-type="category" data-id="' + row.id + '"  data-toggle="modal" data-target="#create_modal">' +
                        '   <i class="fa fa-edit"></i>' +
                        '</a>';
                    html += '' +
                        '<div class="tooltip_copy grid-row-copy pull-right myicon" id="CopyOrginalPath" data-orginal="/LFM/DownloadFile/ID/' + row.id + '">' +
                        '    <input type="hidden" id="orginal_copy" value="/LFM/DownloadFile/ID/' + row.id + '">' +
                        '        <i id="copy_path" class="fa fa-copy button-green" data-clipboard-target="orginal_copy"></i><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                        '</div>';


                }
                else {
                    html += '<a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="' + row.type + '" data-id = "' + row.id + '" data-parent-id="' + row.category_id + '" >' +
                        '<i class="fa fa-trash"></i>' +
                        '</a>';
                    html += '' +
                        '<div class="tooltip_copy grid-row-copy pull-right myicon" id="CopyOrginalPath" data-orginal="/LFM/DownloadFile/ID/' + row.id + '">' +
                        '    <input type="hidden" id="orginal_copy" value="/LFM/DownloadFile/ID/' + row.id + '">' +
                        '        <i id="copy_path" class="fa fa-copy button-green" data-clipboard-target="orginal_copy"></i><span class="tooltiptext" id="myTooltip">Click to Copy</span>' +
                        '</div>';


                }


                return html;

            }
        },
    ];
    return columns ;
}
function add_path_column() {
    var column = {
            title: "Path",
            data: "path",
            name: "path",
            mRender: function (data, type, row) {
                var path = '/' ;
                    $.each(row.Path,function (key,value) {
                        path += value.title+'/';
                    });
                    return path ;
            },
        } ;
    return column ;
}

function set_page_html(th) {
    var html =
        '<table class="table table-bordered" id="grid_media_manager">' +
        '<thead>' +
        '<tr>' ;
         $.each(th , function( index, value ) {
               html +='<th>'+value+'</th>';
          });
     html +=
        '</tr>' +
        '</thead>' +
        '</table>' ;
    $('.panel-body').empty();
    $('.panel-body').append(html) ;

}

function set_attribute(id,cat_name) {
    var section = $('#refresh_page').attr('data-section');
    var callback = $('#refresh_page').attr('data-callback');
    $(".uploadfile").attr('href', base_url+'/LFM/FileUpload/' + id+'/'+callback+'/'+section);
    $("#refresh_page").attr('data-id', id);
    //$("#refresh_page").attr('data-type', 'list');
    $(".create_category").attr('href',base_url + '/LFM/ShowCategories/create/' + id+'/'+callback+'/'+section);
    $( "#refresh_page" ).attr('data-category-name' , cat_name);
}
</script>