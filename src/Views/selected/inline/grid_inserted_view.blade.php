<div style="width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;padding-top: 1%;">
    <table style="width: 100%; max-width: 100%; margin-bottom: 1rem; border-spacing: 0;background-color: transparent; border-collapse: separate!important;@if(in_array(app()->getLocale(),config('laravel_file_manager.lang_rtl')))direction: rtl;text-align: right; @endif">
        <thead style="display: table-header-group;vertical-align: middle;border-color: inherit;">
        <tr>
            <th style="vertical-align: inherit;border-color: inherit;display: table-cell;padding: .75rem;border-top: 1px solid #dee2e6;background-color: white;box-sizing: content-box;  border-bottom: 2px solid #dee2e6;">@lang('filemanager.row')</th>
            <th style="vertical-align: inherit;border-color: inherit;display: table-cell;padding: .75rem;border-top: 1px solid #dee2e6;background-color: white;box-sizing: content-box;  border-bottom: 2px solid #dee2e6;">@lang('filemanager.name')</th>
            @if(!$show)
                <th style="vertical-align: inherit;border-color: inherit;display: table-cell;padding: .75rem;border-top: 1px solid #dee2e6;background-color: white;box-sizing: content-box;  border-bottom: 2px solid #dee2e6;">@lang('filemanager.action')</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @php( $i =0)
        @foreach($data as $file_item)
            <tr id="{{$section}}_{{$file_item['file']['id']}}_trash_insert_li">
                <td style="box-sizing: content-box;padding: .75rem;border-top: 1px solid #dee2e6;">{{$i}}</td>
                @php($i++)
                <td style="box-sizing: content-box;padding: .75rem;border-top: 1px solid #dee2e6;"><a target="_blank"  href="{{$file_item['full_url']}}">{{$file_item['file']['original_name']}}</a></td>
                @if(!$show)
                    <td style="box-sizing: content-box;padding: .75rem;border-top: 1px solid #dee2e6;">
                        <a target="_blank"  style="float:right;" id="{{$section}}_trash_inserted" data-type="file" data-section="{{$section}}" data-file_id="{{$file_item['file']['id']}}">
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
