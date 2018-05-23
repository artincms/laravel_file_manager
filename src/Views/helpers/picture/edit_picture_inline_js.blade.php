<script type="text/javascript">
    //-------------------------------------------------------------------------------------------------------------//
    //show orginal tab
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
                enableExif: true,
                viewport: {width: width_orginal_viewport, height:  height_orginal_viewport},
                boundary: {width: width_orginal, height: height_orginal},
                setZoom:'0.1'

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



        /*end large script*/
        //----------------------------------------------------------------------------------------------------------//

    });
    //--------------------------------------------------------------------------------------------------------------//
    //Large script
    $(document).off('click','#tab_img_large');
    $(document).on('click','#tab_img_large',function () {
        //---------------------------------------------------------------------------------------------------------------------------------//
        //large script
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
                enableExif: true,
                viewport: {width: width_large * rate_large, height: height_large * rate_large},
                boundary: {width: width_large, height: height_large},
                setZoom:'0.1'
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

    //--------------------------------------------------------------------------------------------------------------//
    //Medium script
    $(document).off('click','#tab_img_meidum');
    $(document).on('click','#tab_img_meidum',function () {
        var width_medium_config = {{ config('laravel_file_manager.size_medium.width')}} ;
        var height_medium_config = {{ config('laravel_file_manager.size_medium.height')}} ;
        var rate_medium = width_medium_config / height_medium_config;
        width_medium_div = $('.crope_medium').width();
        $('#img_medium').addClass('hidden') ;
        if (width_medium_config > width_medium_div) {
            var width_medium = width_medium_div;
            var height_medium = rate_medium * width_medium_div;
        } else {
            var width_medium = width_medium_config;
            var height_medium = rate_medium * width_medium_config;
        }
        var crop_medium = document.getElementById('image_medium');
        var medium_croppie = new Croppie(crop_medium, {
            enableExif: true,
            viewport: {width: width_medium * rate_medium, height: height_medium * rate_medium},
            boundary: {width: width_medium, height: height_medium},
        });
        medium_croppie.bind({
            url: "{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' =>'medium'])}}",
        });
        $(".crop_medium").append('<button type="button" id="crope_button_medium" class="btn btn-primary">Crope</button>');
        $(document).off("click", '#crope_button_medium');
        $(document).on('click', '#crope_button_medium', function () {
            medium_croppie.result('base64').then(function(base64) {
                crop_type = 'medium' ;
                send_croped(base64 , crop_type) ;
                $('#img_medium').attr('src' ,base64)
            });
        });
    });

    //--------------------------------------------------------------------------------------------------------------//
    //small script
    $(document).off('click','#tab_img_small');
    $(document).on('click','#tab_img_small',function () {
        var width_small_config = {{ config('laravel_file_manager.size_small.width')}} ;
        var height_small_config = {{ config('laravel_file_manager.size_small.height')}} ;
        var rate_small = width_small_config / height_small_config;
        width_small_div = $('.crope_small').width();
        $('#img_small').addClass('hidden') ;
        if (width_small_config > width_small_div) {
            var width_small = width_small_div;
            var height_small = rate_small * width_small_div;
        } else {
            var width_small = width_small_config;
            var height_small = rate_small * width_small_config;
        }
        var crop_small = document.getElementById('image_small');
        var small_croppie = new Croppie(crop_small, {
            enableExif: true,
            viewport: {width: width_small * rate_small, height: height_small * rate_small},
            boundary: {width: width_small, height: height_small},
        });
        small_croppie.bind({
            url: "{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' =>'small'])}}",
        });
        $(".crop_small").append('<button type="button" id="crope_button_small" class="btn btn-primary">Crope</button>');
        $(document).off("click", '#crope_button_small');
        $(document).on('click', '#crope_button_small', function () {
            small_croppie.result('base64').then(function(base64) {
                crop_type = 'small' ;
                send_croped(base64 , crop_type) ;
            });
        });
    });
    //--------------------------------------------------------------------------------------------------------------//
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
                if(result.success)
                {
                    $('#tab_img_medium').tab('show') ;

                }
                console.log(result) ;
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

</script>
