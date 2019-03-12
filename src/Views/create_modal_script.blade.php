<script type="text/javascript">
    var {{LFM_CheckFalseString($section)}}available = {{$available}} ;
    $(document).off("click", '#{{$button_id}}');
    $(document).on('click', '#{{$button_id}}', function (e) {
        var src = $(this).attr('data-href');
        var iframe = $('#{{LFM_CheckFalseString($section)}}_iframe');
        iframe.contents().find("body").html('');
        iframe.contents().find("body").html(lfm_generate_loader_html('@lang('filemanager.please_wait')'));
        iframe.attr("src",src);
        if ({{LFM_CheckFalseString($section)}}available > 0)
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
