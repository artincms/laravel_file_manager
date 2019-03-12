<div class="modal fade " id="{{$modal_id}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95% !important;max-width: 95% !important;max-height:90% !important;margin: 5px auto !important;">
        <div class="modal-content">
            <div class="modal-header"  style="background-color: #f5f5f5;border: 1px solid #e3e3e3;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                                              box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                <h5 class="modal-title">{!!$header!!}</h5>
                <button type="button" class="close close_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;max-height:  calc(100vh - 145px) ;height:  calc(100vh - 145px) ;">
                <iframe class="modal_iframe" src="" id="{{LFM_CheckFalseString($section)}}_iframe" style="width: 100%;max-height:  calc(100vh - 195px) ;border: none;height:  calc(100vh - 195px) ;"></iframe>
            </div>
            <div class="modal-footer" style="background-color: #f5f5f5;border: 1px solid #e3e3e3;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                                              box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                <button type="button" class="btn btn-secondary" id="close_button_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal">@lang('filemanager.cancel')</button>
                <button type="button" class="btn btn-primary" id="modal_insert_{{LFM_CheckFalseString($modal_id)}}" >@lang('filemanager.insert')</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="create_erro_modal_{{$section}}" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f5f5f5;border: 1px solid #e3e3e3;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                                             box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                <h5 class="modal-title">{!!$header!!}</h5>
                <button type="button" class="close close_error_modal_{{LFM_CheckFalseString($modal_id)}}" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center ;min-height: 275px">
                <div class="modal_body_error_contet">
                    <h2>@lang('filemanager.you_reach_your_maximum_file_inserted')</h2>
                    <h5>@lang('filemanager.for_insert_new_file_you_should_remove_previus_inserted_file')</h5>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f5f5f5;border: 1px solid #e3e3e3;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
                                              box-shadow: inset 0 1px 1px rgba(0,0,0,.05);">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">@lang('filemanager.ok')</button>
            </div>
        </div>
    </div>
</div>
