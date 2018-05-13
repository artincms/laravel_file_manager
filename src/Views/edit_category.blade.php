@extends('laravel_file_manager::layouts.layout')
@section('content')

    @if($messages)
        @foreach($messages as $message)
            <div class="alert alert-success">
                <strong>Success!</strong> {{$message}}.
            </div>
        @endforeach
    @endif
    <form id="create_category_form" class="search-form">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="title" class="control-label">Name</label>
            <input id="title" class="form-control" placeholder="Category Name" type="text" name="title" value="{{$category->title}}">
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Name</label>
            <input id="description" class="form-control" placeholder="some description .." type="text" name="description" value="{{$category->description}}">
        </div>
        <div class="form-group">
            <label data-error="wrong" data-success="right" for="orangeForm-name">Select parrent of Categorie</label>
            <select class="form-control select_category" id="sel1" name="parent_category_id">
                <option value="0">none</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($category_id == $category->id) selected @endif>{{$category->title}}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" id="btn_submit_create_category">Submit</button>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).off("click", '#btn_submit_create_category');
        $(document).on('click', '#btn_submit_create_category', function (e) {
            e.preventDefault();
            var formElement = document.querySelector('#create_category_form');
            var formData = new FormData(formElement);
            $('#create_category_form .error_msg').html('');
            $("#create_category_form .input_with_validation_error").removeClass("input_with_validation_error");
            category_save(formData);
        });

        function category_save(FormData) {
            $.ajax({
                type: "POST",
                url: "{{route('LFM.StoreCategory')}}",
                data: FormData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.success == true) {
                        $("#create_category_form").empty();
                        $(".create_category").html(result.html);
                        $('#message').html('<div class="alert alert-success"><strong>Success!</strong>Your Category Add Successfully</div>');
                    }
                },
                error: function (e) {
                }
            });
        }
        $('.select_category').select2({});
    </script>
@endsection