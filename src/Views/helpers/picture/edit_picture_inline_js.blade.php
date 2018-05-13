<script type="text/javascript">
    //--------------------------------------------------------------------------------------------------------------//
    //orginal script
    $(document).ready(function() {
        $("#img_orginal").on('load',function() {
            var width_orginal = $('#img_orginal').width();
            var height_orginal = $('#img_orginal').height();
            var rate_orginal = width_orginal / height_orginal;
            var width_orginal_div = $('#image_orginal').width() ;
            if(width_orginal_div < width_orginal)
            {
                width_orginal = width_orginal_div ;
                var width_rate = width_orginal / width_orginal_div ;
                height_orginal = width_rate * height_orginal ;
            }

            if (rate_orginal > 1) {
                var width_orginal_viewport = width_orginal/rate_orginal;
                var height_orginal_viewport = height_orginal/rate_orginal;
            }
            else if(rate_orginal == 1){
                var width_orginal_viewport = width_orginal * 0.8;
                var height_orginal_viewport = height_orginal* 0.8;
            }
            else
            {
                var width_orginal_viewport = width_orginal*rate_orginal;
                var height_orginal_viewport = height_orginal*rate_orginal;
            }
            console.log(height_orginal,height_orginal,width_orginal_viewport,height_orginal_viewport) ;
            $('#img_orginal').addClass('hidden') ;
            var crop_orginal = document.getElementById('image_orginal');
            var orginal_croppie = new Croppie(crop_orginal, {
                viewport: {width: width_orginal_viewport, height:  height_orginal_viewport},
                boundary: {width: width_orginal, height: height_orginal},
                showZoomer: false,
            });
            orginal_croppie.bind({
                url: "{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' =>'orginal'])}}",

            });
            $(".crop_orginal").append('<button type="button" id="crope_button_orginal" class="btn btn-primary">Crope</button>');
            $(document).off("click", '#crope_button_orginal');
            $(document).on('click', '#crope_button_orginal', function () {
                orginal_croppie.result('base64').then(function(base64) {
                    crop_type = 'orginal' ;
                    send_croped(base64 , crop_type) ;
                });

            });
        });
        //---------------------------------------------------------------------------------------------------------------------------------//
        //large script
        $("#img_large").on('load',function() {
            var width_large_config = {{ config('laravel_file_manager.size_large.width')}} ;
            var height_large_config = {{ config('laravel_file_manager.size_large.height')}} ;
            var rate_large = width_large_config / height_large_config;
            width_large_div = $('.crope_large').width();
            $('#img_large').addClass('hidden') ;
            if (width_large_config > width_large_div) {
                var width_large = width_large_div;
                var height_large = rate_large * width_large_div;
            } else {
                var width_large = width_large_config;
                var height_large = rate_large * width_large_config;
            }
            var crop_large = document.getElementById('image_large');
            var large_croppie = new Croppie(crop_large, {
                viewport: {width: width_large * rate_large, height: height_large * rate_large},
                boundary: {width: width_large, height: height_large},
                showZoomer: false,
            });
            large_croppie.bind({
                url: "{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' =>'large'])}}",
            });
            $(".crop_large").append('<button type="button" id="crope_button_large" class="btn btn-primary">Crope</button>');
            $(document).off("click", '#crope_button_large');
            $(document).on('click', '#crope_button_large', function () {
                large_croppie.result('base64').then(function(base64) {
                    crop_type = 'large' ;
                    send_croped(base64 , crop_type) ;
                });
            });

        });


        /*end large script*/
        //----------------------------------------------------------------------------------------------------------//

    });


    //send crop imagte to database
    function send_croped(base64 , crop_type) {
        $.ajax({
            type: "POST",
            url: "{{route('LFM.StoreCropedImage')}}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: "json",
            data :{
                file_id:{{ $file->id }} ,
        category_id:{{ $file->category_id }} ,
        crop_type : crop_type ,
            crope_image : base64


    },
        success: function (result) {
            console.log(result) ;
        },
        error: function (e) {
            console.log(e);
        }
    });
    }

</script>
