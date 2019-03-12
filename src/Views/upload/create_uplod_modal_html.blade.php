<div class="modal fade " id="create_upload_{{$section}}_{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95% !important;max-width: 95% !important;max-height:90% !important;margin: 5px auto !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="{{$section}}_body_upload_form" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                <iframe class="modal_iframe" id="{{LFM_CheckFalseString($section)}}_iframe_upload" style="width: 100%;max-height:  calc(100vh - 195px) ;border: none;height:  calc(100vh - 195px) ;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="create_erro_modal_upload" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$header}}</h5>
            </div>
            <div class="modal-body" style="text-align: center ;min-height: 275px">
                <h2>@lang('filemanager.reach_maximum_file_upload')</h2>
                <h5>@lang('filemanager.for_upload_remove_previus_uploadded_file')</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">@lang('filemanager.ok')</button>
            </div>
        </div>
    </div>
</div>