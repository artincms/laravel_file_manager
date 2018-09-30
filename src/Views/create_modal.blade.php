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
<script type="text/javascript">
    var {{LFM_CheckFalseString($section)}}_available = {{$available}} ;
    $(document).off("click", '#{{$button_id}}');
    $(document).on('click', '#{{$button_id}}', function (e) {
        var src = $(this).attr('data-href');
        var iframe = $('#{{LFM_CheckFalseString($section)}}_iframe');
        iframe.contents().find("body").html('');
        iframe.contents().find("body").html(lfm_generate_loader_html('@lang('filemanager.please_wait')'));
        iframe.attr("src",src);
        if ({{LFM_CheckFalseString($section)}}_available > 0)
        {
            $('#{{$modal_id}}').modal('show');
            $( '#{{LFM_CheckFalseString($section)}}_iframe' ).attr( 'src', function ( i, val ) { return val; });
        }
        else
        {
            $('#create_erro_modal_{{$section}}').modal('show');
        }
    });


    //------------------------------------------------------------------------------------//
    //------------------------------------------------------------------------------------//
    $(document).off("click", '#modal_insert_{{LFM_CheckFalseString($modal_id)}}');
    $(document).on('click', '#modal_insert_{{LFM_CheckFalseString($modal_id)}}', function (e) {
        var iframe = $('#{{LFM_CheckFalseString($section)}}_iframe');
        iframe.contents().find("#insert_file").click();
        $('#create_{{$modal_id}}').modal('hide');
    });
    //------------------------------------------------------------------------------------//

    function hidemodal_{{$section}}() {
        $('#{{$modal_id}}').modal('hide');
        $('.modal-backdrop').removeClass();
    }
    //------------------------------------------------------------------------------------//
    function lfm_generate_loader_html(loading_text) {
        var loader_html = '' +
            '<div class="total_loader" style=" background-color: rgba(50, 50, 50, 0.7); color: #d9d9d9; font-weight: bolder; left: 0; top: 0; width: 100%;height: 100%; position: absolute; z-index: 50000;">' +
            '   <div class="total_loader_content" style=" position: absolute; height: 140px; width: 100%; top: 50%; margin-top: -70px;">' +
            '       <div class="spinner_area" style=" width: 50px; margin: 10px auto;">' +
            '           <div class="spinner_rects" style="width: 50px; height: 40px; text-align: center;font-size: 10px;">' +
            '               <div class="" style=" background-color: #d9d9d9;height: 100%;width: 6px;display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;animation: sk-stretchdelay 1.2s infinite ease-in-out;"></div>' +
            '               <div class="rect2" style="background-color: #d9d9d9;height: 100%;width: 6px;display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;animation: sk-stretchdelay 1.2s infinite ease-in-out; -webkit-animation-delay: -1.1s; animation-delay: -1.1s;"></div>' +
            '               <div class="rect3" style="background-color: #d9d9d9;height: 100%;width: 6px;display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;animation: sk-stretchdelay 1.2s infinite ease-in-out;-webkit-animation-delay: -1.0s;animation-delay: -1.0s;"></div>' +
            '               <div class="rect4" style="background-color: #d9d9d9;height: 100%;width: 6px;display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;animation: sk-stretchdelay 1.2s infinite ease-in-out; -webkit-animation-delay: -0.9s; animation-delay: -0.9s;"></div>' +
            '               <div class="rect5" style="background-color: #d9d9d9;height: 100%;width: 6px;display: inline-block; -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;animation: sk-stretchdelay 1.2s infinite ease-in-out; -webkit-animation-delay: -0.8s;animation-delay: -0.8s;"></div>' +
            '           </div>' +
            '       </div>' +
            '       <div class="text_area" style="background-color: rgba(255, 255, 255, 0.8); color: #464646;text-align: center; padding: 10px 0;">' + loading_text + '</div>' +
            '   </div>' +
            '</div>';
        return loader_html;
    }

</script>
