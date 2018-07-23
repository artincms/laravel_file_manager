<div style="width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
    <ul>
        @foreach($data as $file_item)
            <li id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="list-style: none;width:208px;padding: 1%;text-align: center;@if(app()->getLocale() == 'en')float: left ; @else float:right ; @endif">
                <div style="padding: 2%;box-shadow: 1px 1px #dedcdc;background-color: white;    margin: 1%;">
                    <div>
                        @if(!$show)
                            <a target="_blank"  href="" style="float: left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                                <i class="fa fa-trash" style="color: red;"></i>
                            </a>
                        @endif
                    </div>
                    <div style="clear: both;">
                        @if($file_item['file']['icon'] == 'image')
                            <a target="_blank"  href="{{$file_item['full_url']}}"><img src="{{$file_item['full_url_medium']}}" style="height:125px ;width: 175px;"></a>
                        @else
                            <a href="{{$file_item['full_url']}}"><i class="fa  {{$file_item['file']['icon']}} img-file-thumbnail" style="font-size: 107px;margin-bottom: 8%;"></i></a>
                        @endif
                    </div>
                    <div>
                        {{$file_item['file']['original_name']}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')