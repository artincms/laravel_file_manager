<nav aria-label="breadcrumb">
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
<div class="container-fluid">
    <ul class="media-content clearfix col-md-12">
        @if($category)
            <li>
                <div class="media-attachment-info">
                    <div class="clearfix center">
                        <a href="" data-id="{{$category->parent_category_id}}" class="link_to_category">
                            <i class="fa fa-level-up img thumbnail-back"></i>
                        </a>
                    </div>
                </div>
            </li>
        @endif
        @foreach($categories as $category)
            <li>
                <div class="media-attachment-info">
                    <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="category" data-id="{{$category['id']}}" data-parent-id="{{$category['parent_category_id']}}">
                        <i class="fa fa-trash"></i>
                    </a>
                    <a href="{{route('LFM.ShowCategories.Edit',['category_id'=>$category['id']])}}" class="grid-row-edit pull-right myicon" data-toggle="modal" data-target="#create_modal" id="EditCategory" data-type="category"
                       data-id="{{$category['id']}}" data-parent-id="{{$category['parent_category_id']}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" class="media-attachment-chexbox">
                        <input type="checkbox" class="grid-row-checkbox check" data-type="category" data-id="{{$category['id']}}" data-parent-id="{{$category['parent_category_id']}}"/>
                    </a>
                    <div class="clearfix center" data-object="">
                        <a title="{{$category['name']}}" href="" data-id="{{$category['id']}}" class="link_to_category"
                           data-description="{{$category['description']}}"
                        ><i class="fa fa-folder img img-category-thumbnail"></i></a>
                    </div>
                    <div class="text-center">
                        <a class="meida-name" href="" title="{{$category['name']}}">{{$category['name']}}</a>
                    </div>
                </div>
            </li>
        @endforeach
        @foreach($files as $file)
            <li>
                <div class="media-attachment-info">
                    <a href="" class="grid-row-delete pull-right myicon" id="trashfile" data-type="file" data-id="{{$file['id']}}" data-parent-id="{{$file->category_id}}">
                        <i class="fa fa-trash"></i>
                    </a>
                    @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                        <a href="{{route('LFM.EditPicture',['file_id'=>$file['id']])}}" class="grid-row-edit pull-right myicon" id="EditFile" data-type="file" data-id="{{$file['id']}}"
                           data-parent-id="{{$category['parent_category_id']}}" data-toggle="modal" data-target="#create_modal">
                            <i class="fa fa-edit"></i>
                        </a>
                    @else
                        <a href="{{route('LFM.EditFile',['file_id'=>$file['id']])}}" class="grid-row-edit pull-right myicon" id="EditFileName" data-type="file" data-id="{{$file['id']}}"
                           data-parent-id="{{$category['parent_category_id']}}" data-toggle="modal" data-target="#create_modal">
                            <i class="fa fa-edit"></i>
                        </a>
                    @endif
                    <div class="tooltip_copy grid-row-copy pull-right myicon" id="CopyOrginalPath" data-orginal="{{route('LFM.DownloadFile',['type' =>'ID' , 'id'=> $file['id'],])}}">
                        <input type="hidden" id="orginal_copy" value="{{route('LFM.DownloadFile',['type' =>'ID' , 'id'=> $file['id'],])}}">
                        <i id="copy_path" class="fa fa-copy button-green" data-clipboard-target="orginal_copy"></i><span class="tooltiptext" id="myTooltip">Click to Copy</span>
                    </div>
                    <a href="#" class="media-attachment-chexbox">
                        <input type="checkbox" class="grid-row-checkbox check" data-type="file" data-id="{{$file['id']}}" data-parent-id="{{$file->category_id}}" data-name="{{$file->name}}"/>
                    </a>
                    <div class="clearfix text-center showThumbial">
                        @if(in_array($file->mimeType  , config('laravel_file_manager.allowed_pic')))
                            <a>
                                <img id="sweet_image" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type'=>'orginal','default_img'=>'404.png','quality'=>100,'width'=>190,'height'=>127])}}"
                                     class="img-category-thumbnail" title="{{$file['name']}}" data-id="{{$file->id}}" data-user-name="@if ($file->user) {{$file->user->name}}@else public @endif"
                                     data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="Image" data-size="{{$file->size}}"/>
                            </a>
                        @elseif($file->filemimetype->icon_class)
                            <a title="{{$file['name']}}" href="" id="sweet_image" title="{{$file['name']}}" data-id="{{$file->id}}" data-user-name="@if ($file->user) {{$file->user->name}}}@else public @endif"
                               data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="FileIcon" data-icon="{{$file->filemimetype->icon_class}}" data-mime="{{$file->mimeType}}"
                               data-size="{{$file->size}}">
                                <i class="fa {{$file->filemimetype->icon_class}} img-file-thumbnail"></i></a>
                        @else
                            <a title="{{$file['name']}}" href="" id="sweet_image" title="{{$file['name']}}" data-id="{{$file->id}}" data-mime="{{$file->mimeType}}" data-size="{{$file->size}}"
                               data-user-name="@if ($file->user) {{$file->user->name}}}@else public @endif"
                               data-created-date="{{$file->created_at}}" data-updated-date="{{$file->updated_at}}" data-type="File">
                                <i class="fa fa-file img-file-thumbnail"></i></a>
                        @endif
                    </div>
                    <div class="text-center">
                        <a class="meida-name" href="#" title="">{{$file['name']}}</a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel_info">
</div>
@include('laravel_file_manager::helpers.content.content_inline_js')

