<div class="container-fluid">
    <ul class="media-content clearfix col-md-12">
        @foreach($data as $file_item)
            <li class="pull-left" id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="list-style: none;width: 342px;padding: 1%;text-align: center">
                <div class="pull-left" style="padding: 2%;box-shadow: 1px 1px #dedcdc;background-color: white;    margin: 1%;">
                    <div class="insert_thumb_action">
                        @if(!$show)
                            <a target="_blank"  href="" class="pull-left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                                <i class="fa fa-trash" style="color: red;"></i>
                            </a>
                        @endif
                    </div>
                    <div class="text-center" style="margin-bottom: 3%;">
                        @if($file_item['file']['icon'] == 'image')
                            <a target="_blank"  href="{{$file_item['full_url']}}">
                                <img  src="{{$file_item['full_url_large']}}" style=" width:300px;height:180px;">
                            </a>
                        @else
                            <a target="_blank"  href="{{$file_item['full_url']}}">
                                <i class="icon_large fa {{$file_item['file']['icon']}} img-file-thumbnail" style="font-size: 148px;margin-top: 17%;margin-bottom: 3%;"></i>
                            </a>
                        @endif
                    </div>
                    <div class="insert_thumb_info center">
                        <div class="user_detail row">
                            <div class="col-md-12">
                                <h4>{{$file_item['file']['original_name']}}</h4>
                            </div>
                            <i class="fa fa-calendar-plus-o col-md-6" aria-hidden="true">
                                <span class="icon_info_image">{{$file_item['file']['created']}}</span>
                            </i>
                            <i aria-hidden="true" class="fa fa-user col-md-6">
                                <span class="icon_info_image">{{$file_item['file']['user']}} </span>
                            </i>
                            <i  class="fa fa-calendar-o col-md-6"  aria-hidden="true" style="margin-top: 3%;">
                                <span class="icon_info_image">{{$file_item['file']['updated']}}</span>
                            </i>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')