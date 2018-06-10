<script type="text/javascript">
    //-------------------------------------------------------------------------------------------------------------//
    //show orginal tab
    $(document).ready(function() {
        show_crop_orginal();
    });
    $(document).off('click','#tab_img_large');
    $(document).on('click','#tab_img_large',function () {
        show_crop_orginal();
    });
    function show_crop_orginal()
    {
        $('#show_edit_picture').html('<div class="crop_orginal" id = "image_orginal"><img id="img_orginal" src="{{LFM_GenerateDownloadLink('ID',$file->id,'orginal')}}"></div>');
            var width_orginal = $('#img_orginal').width();
            var height_orginal = $('#img_orginal').height();
            if(width_orginal == 0 || height_orginal == 0)
            {
                width_orginal = 800 ;
                height_orginal = 600 ;
            }

            var width_orginal_div = $('#image_orginal').width() ;
            console.log(width_orginal,height_orginal,width_orginal_div);
            var rate_orginal = width_orginal / height_orginal;
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
            $('#img_orginal').addClass('hidden') ;
            var crop_orginal = document.getElementById('image_orginal');
            var orginal_croppie = new Croppie(crop_orginal, {
                enableExif: true,
                viewport: {width: width_orginal_viewport, height:  height_orginal_viewport},
                boundary: {width: width_orginal, height: height_orginal},
            });
            orginal_croppie.bind({
                url: "{{LFM_GenerateDownloadLink('ID',$file->id,'orginal')}}",
            });
            $(".crop_orginal").append('<button type="button" id="crope_button_orginal" class="btn btn-primary hidden"></button>');
            $(document).off("click", '#crope_button_orginal');
            $(document).on('click', '#crope_button_orginal', function () {
                    orginal_croppie.result('base64').then(function(base64) {
                    crop_type = 'orginal' ;
                    swal({
                        title: '@lang('filemanager.result')',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        reverseButtons: true,
                        imageUrl: base64,
                        imageWidth:width_orginal_viewport,
                        imageHeight:height_orginal_viewport,
                        cancelButtonText:'@lang('filemanager.cancel')',
                        confirmButtonText:'@lang('filemanager.ok')',
                    }).then((result) => {
                        if (result.value) {
                            $('#image_orginal').append(generate_loader_html('@lang('filemanager.please_wait')'));
                            var res = send_croped(base64 , crop_type) ;
                            if (res)
                            {
                                show_crop_orginal() ;
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
        console.log(FormData);
        $.ajax({
            type: "POST",
            url: "{{route('LFM.StoreEditPictureName')}}",
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
            '           <input id="picture_name" class="form-control" placeholder="@lang('filemanager.file_name_placeholder')" type="text" name="name" value="{{$file->originalName}}">' +
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
        url: "{{route('LFM.StoreCropedImage')}}",
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
