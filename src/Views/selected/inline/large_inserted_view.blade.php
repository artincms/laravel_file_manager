<div style="width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
<ul style="clear: both;">
        @foreach($data as $file_item)
            <li id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="@if(app()->getLocale() == 'en')float: left ; @else float:right ; @endif list-style: none;width: 343px;padding: 1%;text-align: center;">
                <div style="padding: 2%;box-shadow: 1px 1px #dedcdc;background-color: white;    margin: 1%;float: left">
                    <div>
                        @if(!$show)
                            <a target="_blank"  href="" style="float: left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                                <i class="fa fa-trash" style="color: red;"></i>
                            </a>
                        @endif
                    </div>
                    <div style="margin-bottom: 3%;text-align: center">
                        @if($file_item['file']['icon'] == 'image')
                            <a target="_blank"  href="{{$file_item['full_url']}}">
                                <img  src="{{$file_item['full_url_large']}}" style=" width:300px;height:180px;">
                            </a>
                        @else
                            <a target="_blank"  href="{{$file_item['full_url']}}">
                                <i class="fa {{$file_item['file']['icon']}}" style="font-size: 148px;margin-top: 17%;margin-bottom: 3%;"></i>
                            </a>
                        @endif
                    </div>
                    <div>
                        <div>
                            <div style="width: 100%;">
                                <h4>{{$file_item['file']['original_name']}}</h4>
                            </div>

                            <i style="width: 50%;float: left;text-align: left;" class="fa fa-calendar-plus-o" aria-hidden="true">
                                <span>{{$file_item['file']['created']}}</span>
                            </i>
                            <i aria-hidden="true" class="fa fa-user" style="width: 50%;float: left;text-align: left;">
                                <span>{{$file_item['file']['user']}} </span>
                            </i>
                            <i  class="fa fa-calendar-o"  aria-hidden="true" style="margin-top: 3%;width: 50%;float: left;text-align: left;">
                                <span>{{$file_item['file']['updated']}}</span>
                            </i>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')