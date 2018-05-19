@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/packages/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/select2/js/select2.js')}}"></script>
@endsection
@section('content')
        <div class="alert alert-danger hidden" id="show_error">
           <ul id="show_edit_category_error">

           </ul>
        </div>
    @if($messages)
        @foreach($messages as $message)
            <div class="alert alert-success">
                <strong>Success!</strong> {{$message}}.
            </div>
        @endforeach
    @endif
    <form id="create_category_form" class="search-form">
        {!! csrf_field() !!}
        <input type="hidden" value="{{$category->id}}" name="id">
        <div class="form-group">
            <label for="title" class="control-label">Name</label>
            <input id="title" class="form-control" placeholder="Category Name" type="text" name="title" value="{{$category->title}}">
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Name</label>
            <input id="description" class="form-control" placeholder="some description .." type="text" name="description" value="{{$category->description}}">
        </div>
        <button class="btn btn-primary" id="btn_submit_update_category">Submit</button>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).off("click", '#btn_submit_update_category');
        $(document).on('click', '#btn_submit_update_category', function (e) {
            e.preventDefault();
            var formElement = document.querySelector('#create_category_form');
            var formData = new FormData(formElement);
            $('#create_category_form .error_msg').html('');
            $("#create_category_form .input_with_validation_error").removeClass("input_with_validation_error");
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
                        $('#message').html('<div class="alert alert-success"><strong>Success!</strong>Your Category Update Successfully</div>');
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
    </script>
@endsection