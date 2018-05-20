<div class="container-fluid">
    @php( $i =0)
    <ul>
        @foreach($data as $file_item)
            <li id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li">
                <a href="{{$file_item['full_url']}}">{{$file_item['file']['name']}}</a>
                <span class="pull-left" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                    <i class="fa fa-trash" style="color: red"></i>
                </span>
            </li>
            @php($i = $i+1)
        @endforeach
    </ul>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')