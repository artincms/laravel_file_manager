<div class="container-fluid">
    <ul class="media-content clearfix col-md-12 insert_thumb">
        @foreach($data as $file_item)
            <li class="list_style_none inserted_large" id="{{$section}}_{{$file_item['file']['id']}}_trash_insert">
                <div class="media-attachment-info">
                    <div class="insert_thumb_action">
                        <a href="" class="grid-row-delete pull-right myicon {{$section}}_trash_insert" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <div class="show_image text-center">
                        @if($file_item['file']['icon'] == 'image')
                            <a href="{{$file_item['full_url']}}">
                                <img id="insetred_large_img" src="{{$file_item['full_url']}}">
                            </a>
                        @else
                            <a href="{{$file_item['full_url']}}">
                                <i class="icon_large fa {{$file_item['file']['icon']}} img-file-thumbnail"></i>
                            </a>
                        @endif
                    </div>
                    <div class="insert_thumb_info center">
                        <div class="user_detail row">
                            <i class="fa fa-calendar-plus-o col-md-6" aria-hidden="true">
                                <span class="icon_info_image">{{$file_item['file']['created']}}</span>
                            </i>
                            <i class="fa fa-calendar-o col-md-6" aria-hidden="true">
                                <span class="icon_info_image">{{$file_item['file']['updated']}}</span>
                            </i>
                            <i class="fa fa-user col-md-6 margin-top-4" aria-hidden="true">
                                <span class="icon_info_image">{{$file_item['file']['user']}} </span>
                            </i>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')