<nav aria-label="breadcrumb">
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
<table class="table table-bordered" id="search_media_datatable" style="width: 100%;">
    <thead>
    <tr>
        <th></th>
        <th>name</th>
        <th>user</th>
        <th>Path</th>
        <th>action</th>
    </tr>
    </thead>
    <tbody>
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
            <td>@if ($category->user) {{$category->user->name}}@else public @endif</td>
            <td>{{LFM_GetFoolderPath($category->id,$category->title)}}</td>
            <td>
                <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="category" data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="">
                    <i class="fa fa-trash"></i>
                </a>
                <a href="{{route('LFM.ShowCategories.Edit',['category_id'=>LFM_getEncodeId($category['id'])])}}" class="grid-row-edit pull-right myicon" data-category-name="{{$category['title']}}" data-toggle="modal" data-target="#create_modal" id="EditCategory" data-type="category"
                   data-id="{{LFM_getEncodeId($category['id'])}}" data-parent-id="">
                    <i class="fa fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
    @foreach($files as $file)
        <tr>
            <td><a href="#" class="media-attachment-chexbox">
                    <input type="checkbox" class="grid-row-checkbox check" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}" data-name="{{$file->name}}"/>
                </a>
            </td>
            <td>
                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                    <a id="sweet_image" class="blue" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif "
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="Image" data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}"
                       data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}" data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-orginal-path="{{$file->public_orginal_link}}" data-public-large-path="{{$file->public_large_link}}" data-public-medium-path="{{$file->public_medium_link}}"
                       data-public-small-path="{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa fa-image icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                    </a>

                @elseif($file->filemimetype->icon_class)
                    <a title="{{$file['originalName']}}" href="" id="sweet_image" data-humman_size="{{$file->humman_size}}" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}"
                       data-user-name="@if ($file->user) {{$file->user->name}}}@else public @endif"
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="FileIcon" data-icon="{{$file->filemimetype->icon_class}}" data-mime="{{$file->mimeType}}"
                       data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                       data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-orginal-path="{{$file->public_orginal_link}}" data-public-large-path="{{$file->public_large_link}}" data-public-medium-path="{{$file->public_medium_link}}"
                       data-public-small-path="{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa {{$file->filemimetype->icon_class}} icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                @else
                    <a title="{{$file['originalName']}}" href="" id="sweet_image" data-humman_size="{{$file->humman_size}}" title="{{$file['originalName']}}" data-id="{{LFM_getEncodeId($file->id)}}" data-mime="{{$file->mimeType}}"
                       data-size="{{$file->size}}"
                       data-user-name="@if ($file->user) {{$file->user->name}}}@else public @endif"
                       data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="File"
                       data-size="{{$file->size}}" data-humman_size="{{$file->humman_size}}" data-humman_size_large="{{$file->humman_size_large}}" data_humman_size_medium="{{$file->humman_size_medium}}"
                       data-humman_size_small="{{$file->humman_size_small}}"
                       @if(in_array(-1,LFM_GetAllParentId((int)$file->category_id)))
                       data-public-orginal-path = "{{$file->public_orginal_link}}" data-public-large-path = "{{$file->public_large_link}}" data-public-medium-path = "{{$file->public_medium_link}}" data-public-small-path = "{{$file->public_small_link}}"
                            @endif
                    >
                        <i class="fa fa-file icon_file_list margin-right-1"></i>{{$file['originalName']}}</a>
                @endif
            </td>
            <td>@if ($file->user) {{$file->user->name}}@else public @endif</td>
            <td>{{LFM_GetFoolderPath($file->category_id,$file->category->title)}}</td>
            <td>
                <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}" data-parent-id="{{LFM_getEncodeId($file->category_id)}}">
                    <i class="fa fa-trash"></i>
                </a>
                @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                    <a href="{{route('LFM.EditPicture',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit pull-right myicon" id="EditFile" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                       data-toggle="modal" data-target="#create_modal">
                        <i class="fa fa-edit"></i>
                    </a>
                @else
                    <a href="{{route('LFM.EditFile',['file_id'=>LFM_getEncodeId($file['id'])])}}" class="grid-row-edit pull-right myicon" id="EditFileName" data-type="file" data-id="{{LFM_getEncodeId($file['id'])}}"
                       data-toggle="modal" data-target="#create_edit_file_name_modal">
                        <i class="fa fa-edit"></i>
                    </a>
                @endif
                <div class="tooltip_copy grid-row-copy pull-right myicon" id="CopyOrginalPath" data-orginal="{{LFM_GenerateDownloadLink('ID'),$file['id']}}">
                    <input type="hidden" id="orginal_copy" value="{{LFM_GenerateDownloadLink('ID'),$file['id']}}">
                    <i id="copy_path" class="fa fa-link link_fontawsome" data-clipboard-target="orginal_copy"></i><span class="tooltiptext" id="myTooltip">Click to Copy</span>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#search_media_datatable').DataTable({
            "searching": false
        });
    });
</script>