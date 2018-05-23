@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/packages/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/select2/js/select2.js')}}"></script>
@endsection
@section('content')
        <div class="alert alert-danger hidden" id="show_error">
           <ul id="show_edit_category_error"></ul>
        </div>
    <form id="create_category_form" class="search-form">
        {!! csrf_field() !!}
        <input type="hidden" value="{{$category->id}}" name="id">
        <div class="form-group">
            <label for="title" class="control-label">Title</label>
            <input id="title" class="form-control" placeholder="Category Name" type="text" name="title" value="{{$category->title}}">
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <input id="description" class="form-control" placeholder="some description .." type="text" name="description" value="{{$category->description}}">
        </div>
        <div class="form-group">
            <label data-error="wrong" data-success="right" for="orangeForm-name">Select parrent of Categorie</label>
            <select class="form-control select_category" id="sel1" name="parent_category_id" disabled>
                <option value="0">none</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($parent_category_id == $category->id) selected  @endif>{{$category->title}}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary hidden" id="btn_submit_edit_category">Submit</button>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).off("click", '#btn_submit_edit_category');
        $(document).on('click', '#btn_submit_edit_category', function (e) {
            e.preventDefault();
            var formElement = document.querySelector('#create_category_form');
            var formData = new FormData(formElement);
            $('#create_category_form').append(generate_loader_html('لطفا منتظر بمانید...'));
            category_save(formData);
            if(typeof parent.refresh !== 'undefined')
            {
                parent.refresh() ;
            }
        });

        function category_save(FormData) {
            $.ajax({
                type: "POST",
                url: "{{route('LFM.UpdateCategory')}}",
                data:FormData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.success == true) {
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