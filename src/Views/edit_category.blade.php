@extends('laravel_file_manager::layouts.master')
@section('content')
        <div class="alert alert-danger hidden" id="show_error">
           <ul id="show_edit_category_error"></ul>
        </div>
    <form id="create_category_form" class="search-form filemanager_cateogry_form">
        {!! csrf_field() !!}
        <input type="hidden" value="{{LFM_getEncodeId($category->id)}}" name="id">
        <div class="form-group">
            <label for="title" class="control-label">@lang('filemanager.title')</label>
            <input id="title" class="form-control" placeholder="@lang('filemanager.category_name_placeholder')" type="text" name="title" value="{{$category->title}}">
        </div>
        <div class="form-group">
            <label for="description" class="control-label">@lang('filemanager.description')</label>
            <input id="description" class="form-control" placeholder="@lang('filemanager.category_description_placeholder')" type="text" name="description" value="{{$category->description}}">
        </div>
        <div class="form-group">
            <label data-error="wrong" data-success="right" for="orangeForm-name">@lang('filemanager.category_select_parent')</label>
            <select class="form-control select_category" id="sel1" name="parent_category_id" disabled>
                <option value="0">none</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($parent_category_id == $category->id) selected  @endif>{{$category->title}}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary hidden" id="btn_submit_edit_category">Submit</button>
        <button class="btn btn-primary hidden" id="btn_submit_edit_category_close">Submit</button>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).off("click", '#btn_submit_edit_category');
        $(document).on('click', '#btn_submit_edit_category', function (e) {
            e.preventDefault();
            var formElement = document.querySelector('#create_category_form');
            var formData = new FormData(formElement);
            $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
            category_save(formData);

        });
     $(document).off("click", '#btn_submit_edit_category_close');
    $(document).on('click', '#btn_submit_edit_category_close', function (e) {
        e.preventDefault();
        var formElement = document.querySelector('#create_category_form');
        var formData = new FormData(formElement);
        $('#create_category_form').append(generate_loader_html('@lang('filemanager.please_wait')'));
        category_save(formData,true);
        if(typeof parent.refresh !== 'undefined')
        {
            parent.refresh() ;
        }
    });

    function category_save(FormData,close) {
        var close = close || false ;
        $.ajax({
            type: "POST",
            url: "{{lfm_secure_route('LFM.UpdateCategory')}}",
            data:FormData,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success == true) {
                    if(close)
                    {
                        parent.$('#create_edit_category_modal').modal('hide');
                    }
                    else
                    {
                        document.location.reload();
                    }
                    if(typeof parent.refresh !== 'undefined')
                    {
                        parent.refresh() ;
                    }
                }
            },
            error: function (e) {
                $('#show_error').removeClass('hidden');
                $.each(e.responseJSON.errors,function (index,value) {
                    $('#show_edit_category_error').append('<li><span>'+index+':'+value+'</li>');
                });
                $('.total_loader').remove();
            }
        });
    }
    $('.select_category').select2({});
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