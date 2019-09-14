@extends('laravel_file_manager::layouts.master')

@section('content')
    <div class="alert alert-danger hidden" id="show_error">
        <ul id="show_edit_category_error"></ul>
    </div>
    <form id="form_update_file_name" class="search-form filemanager_cateogry_form">
        {!! csrf_field() !!}
        <input type="hidden" value="{{$file->id}}" name="id">
        <div class="form-group">
            <label for="title" class="control-label">@lang('filemanager.name')</label>
            <input id="title" class="form-control" placeholder="@lang('filemanager.file_name_placeholder')" type="text" name="name" value="{{$file->original_name}}">
        </div>
        <button class="btn btn-primary hidden" id="btn_submit_update_file_name"></button>
        <button class="btn btn-primary hidden" id="btn_submit_update_file_name_close"></button>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).off("click", '#btn_submit_update_file_name');
        $(document).on('click', '#btn_submit_update_file_name', function (e) {
            e.preventDefault();
            $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
            var formElement = document.querySelector('#form_update_file_name');
            var formData = new FormData(formElement);
            edit_file_name(formData);
        });

        $(document).off("click", '#btn_submit_update_file_name_close');
        $(document).on('click', '#btn_submit_update_file_name_close', function (e) {
            e.preventDefault();
            $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
            var formElement = document.querySelector('#form_update_file_name');
            var formData = new FormData(formElement);
            edit_file_name(formData,true);

        });

        function edit_file_name(FormData,close) {
            $.ajax({
                type: "POST",
                url: "{{lfm_secure_route('LFM.EditFileName')}}",
                data:FormData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.success == true) {
                        if(typeof parent.refresh !== 'undefined')
                        {
                            parent.refresh() ;
                        }
                        if(close)
                        {
                            parent.$('#create_edit_file_name_modal').modal('hide');
                        }
                        document.location.reload();
                    }
                },
                error: function (e) {
                    $('#show_error').removeClass('hidden');
                    $.each(e.responseJSON.errors,function (index,value) {
                        $('#show_edit_category_error').append('<li><span>'+index+':'+value+'</li>');
                    });
                }
            });
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
@endsection