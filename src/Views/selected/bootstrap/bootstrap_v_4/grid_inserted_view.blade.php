<div class="container-fluid">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Row</th>
            <th>ID</th>
            <th>Name</th>
            @if(!$show)
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php( $i =0)
        @foreach($data as $file_item)
            <tr id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li">
                <td>{{$i}}</td>
                @php($i++)
                <td>{{$file_item['file']['id']}}</td>
                <td><a target="_blank" href="{{$file_item['full_url']}}">{{$file_item['file']['original_name']}}</a></td>
                @if(!$show)
                    <td>
                        <a target="_blank" class="grid-row-delete pull-right myicon" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
                            <i class="fa fa-trash" style="color: red;"></i>
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@include('laravel_file_manager::selected.helpers.inline_js')
