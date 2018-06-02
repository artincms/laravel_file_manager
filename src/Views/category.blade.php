@extends('laravel_file_manager::layouts.master')
@section('content')
        <div class="alert alert-danger hidden" id="show_error">
            <ul id="show_edit_category_error">
            </ul>
        </div>
        <form id="create_category_form" class="search-form filemanager_cateogry_form">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="title" class="control-label">Title</label>
                <input id="title" class="form-control" placeholder="Category Name" type="text" name="title">
            </div>
            <div class="form-group">
                <label for="description" class="control-label">Description</label>
                <input id="description" class="form-control" placeholder="some description .." type="text" name="description">
            </div>
            <div class="form-group">
                <label data-error="wrong" data-success="right" for="orangeForm-name">Select parrent of Categorie</label>
                <select class="form-control select_category" id="parent_category_id" name="parent_category_id">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" @if($category_id == $category->id) selected @endif>{{$category->title}}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary hidden" id="btn_submit_category">Submit</button>
            <button class="btn btn-primary hidden" id="btn_submit_category_close">Submit</button>
        </form>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.category.category_inline_js')
@endsection