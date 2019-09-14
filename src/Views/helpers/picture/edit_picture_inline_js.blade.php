<script type="text/javascript">
    //-------------------------------------------------------------------------------------------------------------//
    //show original tab
    $(document).ready(function() {
        show_crop_original();
    });
    $(document).off('click','#tab_img_large');
    $(document).on('click','#tab_img_large',function () {
        show_crop_original();
    });
    function show_crop_original()
    {
        $('#show_edit_picture').html('<div class="crop_original" id = "image_original"><img id="img_original" src="{{LFM_GenerateDownloadLink('ID',$file->id,'original')}}"></div>');
            var width_original = $('#img_original').width();
            var height_original = $('#img_original').height();
            if(width_original == 0 || height_original == 0)
            {
                width_original = 800 ;
                height_original = 600 ;
            }

            var width_original_div = $('#image_original').width() ;
            var rate_original = width_original / height_original;
            if(width_original_div < width_original)
            {
                width_original = width_original_div ;
                var width_rate = width_original / width_original_div ;
                height_original = width_rate * height_original ;
            }

            if (rate_original > 1) {
                var width_original_viewport = width_original/rate_original;
                var height_original_viewport = height_original/rate_original;
            }
            else if(rate_original == 1){
                var width_original_viewport = width_original * 0.8;
                var height_original_viewport = height_original* 0.8;
            }
            else
            {
                var width_original_viewport = width_original*rate_original;
                var height_original_viewport = height_original*rate_original;
            }
            $('#img_original').addClass('hidden') ;
            var crop_original = document.getElementById('image_original');
            var original_croppie = new Croppie(crop_original, {
                enableExif: true,
                viewport: {width: width_original_viewport, height:  height_original_viewport},
                boundary: {width: width_original, height: height_original},
            });
            original_croppie.bind({
                url: "{{LFM_GenerateDownloadLink('ID',$file->id,'original')}}",
            });
            $(".crop_original").append('<button type="button" id="crope_button_original" class="btn btn-primary hidden"></button>');
            $(document).off("click", '#crope_button_original');
            $(document).on('click', '#crope_button_original', function () {
                    original_croppie.result('base64').then(function(base64) {
                    crop_type = 'original' ;
                    swal({
                        title: '@lang('filemanager.result')',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        reverseButtons: true,
                        imageUrl: base64,
                        imageWidth:width_original_viewport,
                        imageHeight:height_original_viewport,
                        cancelButtonText:'@lang('filemanager.cancel')',
                        confirmButtonText:'@lang('filemanager.ok')',
                    }).then((result) => {
                        if (result.value) {
                            $('#image_original').append(generate_loader_html('@lang('filemanager.please_wait')'));
                            var res = send_croped(base64 , crop_type) ;
                            if (res)
                            {
                                show_crop_original() ;
                            }
                        }
                    });
                });
            });
    }
    //--------------------------------------------------------------------------------------------------------------//
    //Large script
    $(document).off('click','#tab_img_large');
    $(document).on('click','#tab_img_large',function () {
        //---------------------------------------------------------------------------------------------------------------------------------//
        show_crop_large();
    });

    function show_crop_large()
    {
        $('#show_edit_picture').html('<div class="crop_large" id = "image_large"><img id="img_large" src="{{LFM_GenerateDownloadLink('ID',$file->id,'large')}}"></div>');
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
        });
        large_croppie.bind({
            url: "{{LFM_GenerateDownloadLink('ID',$file->id,'large')}}",
        });
        $(".crop_large").append('<button type="button" id="crope_button_large" class="btn btn-primary hidden">Crope</button>');
        $(document).off("click", '#crope_button_large');
        $(document).on('click', '#crope_button_large', function () {
            large_croppie.result('base64').then(function(base64) {
                crop_type = 'large' ;
                swal({
                    title: '@lang('filemanager.result')',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    reverseButtons: true,
                    imageUrl: base64,
                    imageWidth:width_large* rate_large,
                    imageHeight:height_large * rate_large,
                    cancelButtonText:'@lang('filemanager.cancel')',
                    confirmButtonText:'@lang('filemanager.ok')',
                }).then((result) => {
                    if (result.value) {
                    $('#image_large').append(generate_loader_html('@lang('filemanager.please_wait')'));
                    var res = send_croped(base64 , crop_type) ;
                        if (res)
                        {
                            show_crop_large() ;
                        }
                    }
                });
            });
        });
    }

    //--------------------------------------------------------------------------------------------------------------//
    //Medium script
    $(document).off('click','#tab_img_meidum');
    $(document).on('click','#tab_img_meidum',function () {
        show_crop_medium();
    });

    function show_crop_medium()
    {
        $('#show_edit_picture').html('<div class="crop_medium" id = "image_medium"><img id="img_medium" src="{{LFM_GenerateDownloadLink('ID',$file->id,'medium')}}"></div>');
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
            url: "{{LFM_GenerateDownloadLink('ID',$file->id,'medium')}}",
        });
        $(".crop_medium").append('<button type="button" id="crope_button_medium" class="btn btn-primary hidden">Crope</button>');
        $(document).off("click", '#crope_button_medium');
        $(document).on('click', '#crope_button_medium', function () {
            medium_croppie.result('base64').then(function(base64) {
                crop_type = 'medium' ;
                swal({
                    title: '@lang('filemanager.result')',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    reverseButtons: true,
                    imageUrl: base64,
                    imageWidth:width_medium * rate_medium,
                    imageHeight:height_medium * rate_medium,
                    cancelButtonText:'@lang('filemanager.cancel')',
                    confirmButtonText:'@lang('filemanager.ok')',
                }).then((result) => {
                    if (result.value) {
                    $('#image_medium').append(generate_loader_html('@lang('filemanager.please_wait')'));
                    var res = send_croped(base64 , crop_type) ;
                        if (res)
                        {
                            show_crop_medium() ;
                        }
                    }
                });
            });
        });
    }
    //--------------------------------------------------------------------------------------------------------------//
    //small script
    $(document).off('click','#tab_img_small');
    $(document).on('click','#tab_img_small',function () {
        show_crop_small() ;
    });

    function show_crop_small()
    {
        $('#show_edit_picture').html('<div class="crop_small" id = "image_small"><img id="img_small" src="{{LFM_GenerateDownloadLink('ID',$file->id,'small')}}"></div>');
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
            url: "{{LFM_GenerateDownloadLink('ID',$file->id,'small')}}",
        });
        $(".crop_small").append('<button type="button" id="crope_button_small" class="btn btn-primary hidden"></button>');
        $(document).off("click", '#crope_button_small');
        $(document).on('click', '#crope_button_small', function () {
            small_croppie.result('base64').then(function(base64) {
                crop_type = 'small' ;
                swal({
                    title: '@lang('filemanager.result')',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                reverseButtons: true,
                imageUrl: base64,
                imageWidth:width_small* rate_small,
                imageHeight:height_small * rate_small,
                cancelButtonText:'@lang('filemanager.cancel')',
                confirmButtonText:'@lang('filemanager.ok')',
            }).then((result) => {
                if (result.value) {
                    $('#image_small').append(generate_loader_html('@lang('filemanager.please_wait')'));
                    var res = send_croped(base64 , crop_type) ;
                    if (res)
                    //send crop imagte to database
                    {

                        show_crop_small() ;
                        parent.refresh();
                    }
                }
            });
            });
        });
    }

    //--------------------------------------------------------------------------------------------------------------//
    // rename picture
    $(document).off('click','#tab_rename');
    $(document).on('click','#tab_rename',function () {
        rename_picture();
    });

    $(document).off('click','#crope_button_rename');
    $(document).on('click','#crope_button_rename',function (e) {
        e.preventDefault() ;
        var formElement = document.querySelector('#edit_picture_name');
        var formData = new FormData(formElement);
        $('#edit_picture_name').append(generate_loader_html('@lang('filemanager.please_wait')'));
        save(formData);
    });

    function save(FormData) {
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.StoreEditPictureName')}}",
            data: FormData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success == true) {
                    parent.refresh() ;
                    $('#picture_name').val(result.name);
                    $('#show_edit_picture_name_message').html('<div class="alert alert-success" role="alert">'+result.message+'</div>');
                    $('.total_loader').remove();
                }
            },
            error: function (e) {
                $('.total_loader').remove();
            }
        });
    }
    function rename_picture()
    {
        $('#show_edit_picture').html('' +
            '<div class="rename" id = "rename_picture">  ' +
            '   <div id="show_edit_picture_name_message"></div>' +
            '   <form id="edit_picture_name" class="search-form edit_picture_name">' +
            '       <input type="hidden" value="{{LFM_getEncodeId($file->id)}}" name="id">' +
            '       <div class="form-group">' +
            '          <label for="title" class="control-label">@lang('filemanager.name')</label> ' +
            '           <input id="picture_name" class="form-control" placeholder="@lang('filemanager.file_name_placeholder')" type="text" name="name" value="{{$file->original_name}}">' +
            '           <button class="btn btn-primary hidden" id="crope_button_rename">@lang('filemanager.submit')</button>' +
            '       </div>' +
            '   </form>' +
            '</div>');
    }


    //--------------------------------------------------------------------------------------------------------------//

    function send_croped(base64 , crop_type) {
        var res = false ;
        $.ajax({
        type: "POST",
        url: "{{lfm_secure_route('LFM.StoreCropedImage')}}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        async: false,
        data :{
        file_id:{{ $file->id }} ,
        category_id:{{ $file->category_id }} ,
        crop_type : crop_type ,
        crope_image : base64


    },
        success: function (result) {
                if(result.success)
                {
                    parent.refresh() ;
                    res = true ;
                }
            },
        error: function (e) {
            }
    });
        return res ;
    }
    function generate_loader_html(loading_text) {
        var loader_html = '' +
            '<div class="total_loader">' +
            '   <div class="total_loader_content" style="">' +
            '       <div class="spinner_area">' +
            '           <div class="spinner_rects">' +
            '               <div class="rect1"></div>' +
            '               <div class="rect2"></div>' +
            '               <div class="rect3"></div>' +
            '               <div class="rect4"></div>' +
            '               <div class="rect5"></div>' +
            '           </div>' +
            '       </div>' +
            '       <div class="text_area">' + loading_text + '</div>' +
            '   </div>' +
            '</div>';
        return loader_html;
    }
</script>
