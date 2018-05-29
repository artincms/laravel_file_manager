<div class="container-fluid">
    <ul class="media-content clearfix col-md-12 insert_thumb">
        @foreach($data as $file_item)
            <li class="center pull-left col-md-3 col-sm-6" id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="list-style: none;width: 20%;padding: 1%;text-align: center">
                <div style="padding: 2%;box-shadow: 1px 1px #dedcdc;background-color: white;    margin: 1%;">
                    <div class="insert_thumb_action">
                        @if(!$show)
                            <a href="" class="pull-left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                                <i class="fa fa-trash" style="color: red;"></i>
                            </a>
                        @endif
                    </div>
                    <div>
                        @if($file_item['file']['icon'] == 'image')
                            <a href="{{$file_item['full_url']}}"><img src="{{$file_item['full_url_medium']}}" style="height:125px ;width: 175px;"></a>
                        @else
                            <a href="{{$file_item['full_url']}}"><i class="fa  {{$file_item['file']['icon']}} img-file-thumbnail" style="font-size: 148px;margin-top: 8%;"></i></a>
                        @endif
                    </div>
                    <div class="insert_thumb_info center">
                        {{$file_item['file']['originalName']}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')