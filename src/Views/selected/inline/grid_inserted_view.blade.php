<div style="width: 50%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
    <table style="width: 100%; max-width: 100%;margin-bottom: 1rem;background-color: transparent;border-collapse: collapse;">
        <thead>
        <tr>
            <th>@lang('filemanager.row')</th>
            <th>@lang('filemanager.name')</th>
            @if(!$show)
                <th>@lang('filemanager.action')</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php( $i =0)
        @foreach($data as $file_item)
            <tr id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li">
                <td>{{$i}}</td>
                @php($i++)
                <td><a href="{{$file_item['full_url']}}">{{$file_item['file']['originalName']}}</a></td>
                @if(!$show)
                    <td>
                        <a style="float:right;" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
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
