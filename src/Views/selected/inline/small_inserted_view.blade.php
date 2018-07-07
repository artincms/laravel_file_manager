<div style="width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
    @php( $i =0)
    @foreach($data as $file_item)
        <div id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="float: left;background-color: white; padding: 0.5%;box-shadow: 1px 1px #cccccc;margin-top: 1%;    min-width: 45%;    margin: 1%;
            position: relative;display: inline-flex;vertical-align: middle;">
            @if(!$show)
                <a target="_blank"  href="" style="width: 8%;float: left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                    <i class="fa fa-trash" style="    color: red;"></i>
                </a>
            @endif
            <div type="text" style="width: 66%;">
                <a target="_blank"  href="{{$file_item['full_url']}}">{{$file_item['file']['original_name']}}</a>
            </div>
            <span style="float: right">{{$file_item['file']['size']}}</span>
        </div>
    @endforeach
</div>
@include('laravel_file_manager::selected.helpers.inline_js')