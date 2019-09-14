@php($name_column=config('laravel_file_manager.user_name_column'))
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            @if($breadcrumb['type'] =="DisableLink")
                <li class="breadcrumb-item">{{$breadcrumb['title']}}</li>
            @else
                <li class="breadcrumb-item"><a href="#" data-id="{{LFM_getEncodeId($breadcrumb['id'])}}" id="show_grid">{{$breadcrumb['title']}}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
<table class="table table-bordered" id="search_media_datatable" style="width: 100%;">
    <thead>
    <tr>
        <th></th>
        <th>@lang('filemanager.name')</th>
        <th>@lang('filemanager.user')</th>
        <th>@lang('filemanager.path')</th>
        <th>@lang('filemanager.action')</th>
    </tr>
    </thead>
    <tbody>
    @if(count($categories) > 0)
    @foreach($categories as $category)
        <tr>
            <td><a href="#" class="media-attachment-chexbox">
                    <input type="checkbox" class="grid-row-checkbox check" data-type="file" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['category_id'])}}" data-name="{{$category['title']}}"/>
                </a>
            </td>
            </td>
            <td>

                <a title="{{$category['title']}}" href="" data-id="{{LFM_getEncodeId($category['id'])}}" class="link_to_category" data-description="{{$category['description']}}"
                ><i class="fa fa-folder margin-right-1"></i>{{$category['title']}}</a>

            </td>
            <td>@if ($category->user) {{$category->user->$name_column}}@else public @endif</td>
            <td><a  data-id="{{LFM_getEncodeId($category['id'])}}" href="" class="link_to_category">{{LFM_GetFoolderPath($category->id,$category->title)}}</a></td>
            <td>
                <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}">
                    <i class="fa fa-trash"></i>
                </a>
                <a href="{{lfm_secure_route('LFM.ShowCategories.Edit',['category_id'=>LFM_getEncodeId($category['id'])])}}" class="grid-row-edit pull-right myicon" data-category-name="{{$category['title']}}" data-toggle="modal" data-target="#create_edit_category_modal" id="EditCategory" data-type="category"
                   data-id="{{LFM_getEncodeId($category['id'])}}"  data-parent-id="{{LFM_getEncodeId($category['parent_category_id'])}}" >
                    <i class="fa fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
    @endif
    @if(count($files) > 0)
    @foreach($files as $file)
        <tr>
            <td><a href="#" class="media-attachment-chexbox">
                    <input type="checkbox" class="grid-row-checkbox check" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}" data-name="{{$file->$name_column}}"/>
                </a>
            </td>
            <td>
                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                    <a id="sweet_image" class="blue" title="{{$file['original_name']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-user-name="@if ($file->user) {{$file->user->$name_column}}@else public @endif "
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="Image" data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}"
                       data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}" data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-original-path="{{$file->public_original_link}}" data-public-large-path="{{$file->public_large_link}}" data-public-medium-path="{{$file->public_medium_link}}"
                       data-public-small-path="{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa fa-image icon_file_list margin-right-1"></i>{{$file['original_name']}}</a>
                    </a>

                @elseif($file->filemimetype->icon_class)
                    <a title="{{$file['original_name']}}" href="" id="sweet_image" data-humman_size="{{$file->humman_size}}" title="{{$file['original_name']}}" data-id="{{LFM_getEncodeId($file->id)}}"
                       data-user-name="@if ($file->user) {{$file->user->$name_column}}}@else public @endif"
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="FileIcon" data-icon="{{$file->filemimetype->icon_class}}" data-mime="{{$file->mimeType}}"
                       data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                       data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-original-path="{{$file->public_original_link}}" data-public-large-path="{{$file->public_large_link}}" data-public-medium-path="{{$file->public_medium_link}}"
                       data-public-small-path="{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa {{$file->filemimetype->icon_class}} icon_file_list margin-right-1"></i>{{$file['original_name']}}</a>
                @else
                    <a title="{{$file['original_name']}}" href="" id="sweet_image" data-humman_size="{{$file->humman_size}}" title="{{$file['original_name']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-mime="{{$file->mimeType}}"
                       data-size="{{$file->size}}"
                       data-user-name="@if ($file->user) {{$file->user->$name_column}}}@else public @endif"
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="File"
                       data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                       data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-original-path = "{{$file->public_original_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa fa-file icon_file_list margin-right-1"></i>{{$file['original_name']}}</a>
                @endif
            </td>
            <td>@if ($file->user) {{$file->user->$name_column}}@else public @endif</td>
            <td><a class="link_to_category" href="" data-id="{{LFM_getEncodeId($file['category_id'])}}">{{LFM_GetFoolderPath($file->category_id,$file->category->title)}}</a></td>
            <td>
                <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}">
                    <i class="fa fa-trash"></i>
                </a>
                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                    <a href="{{lfm_secure_route('LFM.EditPicture',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit pull-right myicon" id="EditFile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                       data-toggle="modal" data-target="#create_edit_picture_modal" data-file-name="{{$file['original_name']}}">
                        <i class="fa fa-edit"></i>
                    </a>
                @else
                    <a href="{{lfm_secure_route('LFM.EditFile',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit pull-right myicon" id="EditFileName" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                       data-toggle="modal" data-target="#create_edit_file_name_modal"  data-file-name="{{$file['original_name']}}">
                        <i class="fa fa-edit"></i>
                    </a>
                @endif
                <div class="tooltip_copy grid-row-copy pull-right myicon" id="CopyoriginalPath" data-original="{{LFM_GenerateDownloadLink('ID'),$file['id']}}">
                    <input type="hidden" id="original_copy" value="{{LFM_GenerateDownloadLink('ID'),$file['id']}}">
                    <i id="copy_path" class="fa fa-link link_fontawsome" data-clipboard-target="original_copy"></i><span class="tooltiptext" id="myTooltip">Click to Copy</span>
                </div>
            </td>
        </tr>
    @endforeach
    @endif
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#search_media_datatable').DataTable({
            "searching": false
        });
    });
</script>