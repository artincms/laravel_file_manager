<div class="container-fluid">
    @php( $i =0)
    @foreach($data as $file_item)
        <div class="btn-group small_inserted_view col-md-6 pull-left margin-top-1" id="{{$section}}_{{$file_item['file']['id']}}_trash_insert">
            <a href="" class="grid-row-delete pull-left myicon col-md-1 {{$section}}_trash_insert" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                <i class="fa fa-trash"></i>
            </a>
            <div type="text" class="col-md-8">
                <a href="{{$file_item['full_url']}}">{{$file_item['file']['name']}}</a>
            </div>
            <div class="col-md-2 pull-right">{{$file_item['file']['size']}}</div>
        </div>
    @endforeach
</div>
@include('laravel_file_manager::selected.helpers.inline_js')