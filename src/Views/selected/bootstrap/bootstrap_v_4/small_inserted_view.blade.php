<div class="container-fluid">
    @php( $i =0)
    @foreach($data as $file_item)
        <div class="btn-group pull-left " id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li" style="background-color: white; padding: 0.5%;box-shadow: 1px 1px #cccccc;margin-top: 1%;    min-width: 45%;    margin: 1%;">
            @if(!$show)
                <a target="_blank" href="" class="pull-left col-md-1" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                    <i class="fa fa-trash" style="    color: red;"></i>
                </a>
            @endif
            <div type="text" class="col-md-8">
                <a target="_blank" href="{{$file_item['full_url']}}">{{$file_item['file']['original_name']}}</a>
            </div>
            <span class="pull-right">{{$file_item['file']['size']}}</span>
        </div>
    @endforeach
</div>
@include('laravel_file_manager::selected.helpers.inline_js')