<ul class="nav nav-tabs" id="myTab" role="tablist" style="display: none;">
    <li class="nav-item">
        <a class="nav-link active" id="show_grid_tab" data-toggle="tab" href="#show_grid_content" role="tab" aria-controls="home" aria-selected="true"></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="show_list_tab" data-toggle="tab" href="#show_list_content" role="tab" aria-controls="profile" aria-selected="false"></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="show_search_tab" data-toggle="tab" href="#show_search_content" role="tab" aria-controls="messages" aria-selected="false"></a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="show_grid_content" role="tabpanel">
        <nav class="col-md-12 padding-0" aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb['type'] =="DisableLink")
                        <li class="breadcrumb-item">{{$breadcrumb['title']}}</li>
                    @else
                        <li class="breadcrumb-item"><a href="#" data-id="{{LFM_getEncodeId($breadcrumb['id'])}}" class="link_to_category">{{$breadcrumb['title']}}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <div class="container-fluid">
            <ul class="media-content clearfix col-md-12">
                @if($category)
                    <li>
                        <div class="media-attachment-info back_to_category_size">
                            <div class="clearfix center">
                                <a href="" data-id="{{LFM_getEncodeId($category->parent_category_id)}}" class="link_to_category">
                                    <i class="fa fa-level-up img thumbnail-back"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif
                @foreach($categories as $category)
                    <li>
                        <div class="media-attachment-info">
                            <div class="action margin-right-1">
                                <a href="" class="grid-row-delete float-right myicon" id="trashfile" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a href="{{route('LFM.ShowCategories.Edit',['category_id'=>LFM_getEncodeId($category['id'])])}}" class="grid-row-edit float-right myicon" data-toggle="modal" data-target="#create_edit_category_modal" id="EditCategory" data-type="category"
                                   data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-category-name="{{$category['title']}}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                            <a href="#" class="media-attachment-chexbox">
                                <input type="checkbox" class="grid-row-checkbox check" data-view="grid" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}"/>
                            </a>
                            <div class="clearfix center" data-object="">
                                <a title="{{$category['title']}}" href="" data-id="{{LFM_getEncodeId($category['id'])}}" class="link_to_category"
                                   data-description="{{$category['description']}}"
                                ><i class="fa fa-folder img img-category-thumbnail"></i></a>
                            </div>
                            <div class="text-center">
                                <a class="meida-name" href="" title="{{$category['title']}}">{{$category['title']}}</a>
                            </div>
                        </div>
                    </li>
                @endforeach
                @foreach($files as $file)
                        <li>
                        <div class="media-attachment-info">
                            <div class="action margin-right-1">
                                <a href="" class="grid-row-delete float-right myicon" id="trashfile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}">
                                    <i class="fa fa-trash"></i>
                                </a>
                                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                                    <a href="{{route('LFM.EditPicture',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit float-right myicon" id="EditFile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                                       data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-toggle="modal" data-target="#create_edit_picture_modal">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @else
                                    <a href="{{route('LFM.EditFile',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit float-right myicon" id="EditFileName" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                                       data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-toggle="modal" data-target="#create_edit_file_name_modal" data-file-name="{{$file['originalName']}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                                @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                                    <div class="tooltip_copy grid-row-copy float-right myicon" id="CopyOrginalPath" data-orginal="{{LFM_GeneratePublicDownloadLink($file['path'],$file['filename'])}}">
                                        <input type="hidden" id="orginal_public_copy" value="{{LFM_GeneratePublicDownloadLink($file['path'],$file['filename'])}}">
                                        <i id="copy_path" class="fa fa-link link_fontawsome color_green" data-clipboard-target="orginal_public_copy"></i><span class="tooltiptext tootltip_public_path" id="myTooltip">Copy Public Path</span>
                                    </div>
                                @endif
                                <div class="tooltip_copy grid-row-copy float-right myicon" id="CopyOrginalPath" data-orginal="{{LFM_GenerateDownloadLink('ID',$file['id'])}}">
                                    <input type="hidden" id="orginal_copy" value="{{LFM_GenerateDownloadLink('ID',$file['id'])}}">
                                    <i id="copy_path" class="fa fa-link link_fontawsome" data-clipboard-target="orginal_copy"></i><span class="tooltiptext" id="myTooltip">Copy Path</span>
                                </div>
                            </div>
                            <a href="#" class="media-attachment-chexbox">
                                <input type="checkbox" class="grid-row-checkbox check" data-view="grid" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}" data-name="{{$file['originalName']}}"/>
                            </a>
                            <div class="clearfix text-center showThumbial">
                                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                                    <a>
                                        <img id="sweet_image" src="{{LFM_GenerateDownloadLink('ID',$file->id,'small','404.png',100,190,127)}}"
                                             class="img-category-thumbnail" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif "
                                             data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="Image" data-size="{{$file->size}}"
                                             data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                                             data-humman_size_small="{{$file->humman_size_small}}"
                                             @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                                data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                                              @endif                                        />
                                    </a>
                                @elseif($file->filemimetype->icon_class)
                                    <a title="{{$file['originalName']}}" href="" id="sweet_image" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}"
                                       data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                                       data-humman_size_small="{{$file->humman_size_small}}"
                                       data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif"
                                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="FileIcon" data-icon="{{$file->filemimetype->icon_class}}" data-mime="{{$file->mimeType}}"
                                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                            data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                                        @endif
                                    ">
                                        <i class="fa {{$file->filemimetype->icon_class}} img-file-thumbnail"></i></a>
                                @else
                                    <a title="{{$file['originalName']}}" href="" id="sweet_image" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-mime="{{$file->mimeType}}"
                                       data-size="{{$file->size}}" data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}" data-humman-size_large="{{$file->humman_size_large}}"
                                       data-humman-size_medium="{{$file->humman_size_medium}}" data-humman-size_small="{{$file->humman_size_small}}"
                                       data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif"
                                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="File"
                                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                            data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                                        @endif                                    >
                                        <i class="fa fa-file img-file-thumbnail"></i></a>
                                @endif
                            </div>
                            <div class="text-center">
                                <a class="meida-name" href="#" title="">{{$file['originalName']}}</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <script>
            $(document).ready(function () {
                $('#grid_media_manager').DataTable({
                    "searching": false
                });
            });
        </script>
    </div>
    <div class="tab-pane" id="show_list_content" role="tabpanel">
        <nav class="col-md-12 padding-0 " aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb['type'] =="DisableLink")
                        <li class="breadcrumb-item">{{$breadcrumb['title']}}</li>
                    @else
                        <li class="breadcrumb-item"><a href="#" data-id="{{$breadcrumb['id']}}" class="link_to_category">{{$breadcrumb['title']}}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <table class="table table-bordered" id="grid_media_manager" style="width: 100%;">
            <thead>
            <tr>
                <th></th>
                <th>name</th>
                <th>user</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td><a href="#" class="media-attachment-chexbox">
                            <input type="checkbox" class="grid-row-checkbox check" data-view="list" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['category_id'])}}" data-name="{{$category['title']}}"/>
                        </a>
                    </td>
                    </td>
                    <td>

                        <a title="{{$category['title']}}" href="" data-id="{{LFM_getEncodeId($category['id'])}}" class="link_to_category" data-description="{{$category['description']}}"
                        ><i class="fa fa-folder margin-right-1"></i>{{$category['title']}}</a>

                    </td>
                    <td>@if ($category->user) {{$category->user->name}}@else public @endif</td>
                    <td>
                        <a href="" class="grid-row-delete float-right myicon" id="trashfile" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}">
                            <i class="fa fa-trash"></i>
                        </a>
                        <a href="{{route('LFM.ShowCategories.Edit',['category_id'=>LFM_getEncodeId($category['id'])])}}" class="grid-row-edit float-right myicon" data-toggle="modal" data-target="#create_edit_category_modal" id="EditCategory" data-type="category"
                           data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-category-name="{{$category['title']}}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            @foreach($files as $file)
                <tr>
                    <td><a href="#" class="media-attachment-chexbox">
                            <input type="checkbox" class="grid-row-checkbox check" data-view="list" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}" data-name="{{$file['originalName']}}"/>
                        </a>
                    </td>
                    <td>
                        @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                            <a id="sweet_image" class="blue" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif "
                               data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="Image" data-size="{{$file->size}}"
                               data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}" data-humman_size_small="{{$file->humman_size_small}}"
                               @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                    data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                                @endif                        ">
                            <i class="fa fa-image icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                            </a>

                        @elseif($file->filemimetype->icon_class)
                            <a title="{{$file['originalName']}}" href="" id="sweet_image" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}"
                               data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}" data-humman_size_small="{{$file->humman_size_small}}"
                               data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif"
                               data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="FileIcon" data-icon="{{$file->filemimetype->icon_class}}" data-mime="{{$file->mimeType}}"
                               @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                    data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                                @endif                            ">
                                <i class="fa {{$file->filemimetype->icon_class}} icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                        @else
                            <a title="{{$file['originalName']}}" href="" id="sweet_image" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-mime="{{$file->mimeType}}" data-size="{{$file->size}}"
                               data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}" data-humman_size_small="{{$file->humman_size_small}}"
                               data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif"
                               data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="File"
                               @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                                    data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                               @endif
                            >
                                <i class="fa fa-file icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                        @endif
                    </td>
                    <td>@if ($file->user) {{$file->user->name}}@else public @endif</td>
                    <td>
                        <a href="" class="grid-row-delete float-right myicon" id="trashfile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}">
                            <i class="fa fa-trash"></i>
                        </a>
                        @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                            <a href="{{route('LFM.EditPicture',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit float-right myicon" id="EditFile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                               data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-toggle="modal" data-target="#create_edit_picture_modal">
                                <i class="fa fa-edit"></i>
                            </a>
                        @else
                            <a href="{{route('LFM.EditFile',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit float-right myicon" id="EditFileName" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                               data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" data-toggle="modal" data-target="#create_edit_file_name_modal" data-file-name="{{$file['originalName']}}">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                        @if(in_array(-1,LFM_GetAllParentId((int)$parent_id)))
                            <div class="tooltip_copy grid-row-copy float-right myicon" id="CopyOrginalPath" data-orginal="{{LFM_GeneratePublicDownloadLink($file['path'],$file['filename'])}}">
                                <input type="hidden" id="orginal_public_copy" value="{{LFM_GeneratePublicDownloadLink($file['path'],$file['filename'])}}">
                                <i id="copy_path" class="fa fa-link link_fontawsome color_green" data-clipboard-target="orginal_public_copy"></i><span class="tooltiptext" id="myTooltip">Copy Public Path</span>
                            </div>
                        @endif
                        <div class="tooltip_copy grid-row-copy float-right myicon" id="CopyOrginalPath" data-orginal="{{LFM_GenerateDownloadLink('ID',$file['id'])}}">
                            <input type="hidden" id="orginal_copy" value="{{LFM_GenerateDownloadLink('ID',LFM_getEncodeId($file['id']))}}">
                            <i id="copy_path" class="fa fa-link link_fontawsome" data-clipboard-target="orginal_copy"></i><span class="tooltiptext tootltip_public_path" id="myTooltip">Click to Copy</span>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="show_search_content" role="tabpanel">
        <div id="show_search_result"></div>
    </div>
</div>
@include('laravel_file_manager::helpers.content.content_inline_js')


