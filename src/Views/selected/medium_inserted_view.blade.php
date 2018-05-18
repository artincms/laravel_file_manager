<div class="container-fluid">
    <ul class="media-content clearfix col-md-12 insert_thumb">
        @foreach($data as $file_item)
            <li class="list_style_none center col-md-4 inserted_tumb" id="{{$section}}_{{$file_item['file']['id']}}_trash_insert">
                <div class="media-attachment-info">
                    <div class="insert_thumb_action">
                        <a href="" class="grid-row-delete pull-right myicon {{$section}}_trash_insert" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <div class="show_image">
                        @if($file_item['file']['icon'] == 'image')
                            <a href="{{$file_item['full_url']}}"><img id="insetred_thumb_img" src="{{$file_item['full_url']}}"></a>
                        @else
                            <a href="{{$file_item['full_url']}}"><i class="fa icon_thumb {{$file_item['file']['icon']}} img-file-thumbnail"></i></a>
                        @endif
                    </div>
                    <div class="insert_thumb_info center">
                        {{$file_item['file']['name']}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>