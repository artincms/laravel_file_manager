@extends('laravel_file_manager::layouts.master')
@section('content')
        <div class="alert alert-danger hidden" id="show_error">
            <ul id="show_edit_category_error">
            </ul>
        </div>
        <form id="create_category_form" class="search-form filemanager_cateogry_form">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="title" class="control-label">@lang('filemanager.title')</label>
                <input id="title" class="form-control" placeholder="@lang('filemanager.category_name_placeholder')" type="text" name="title">
            </div>
            <div class="form-group">
                <label data-error="wrong" data-success="right" for="orangeForm-name">@lang('filemanager.category_select_parent')</label>
                <select class="form-control select_category" id="parent_category_id" name="parent_category_id">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" @if($category_id == $category->id) selected @endif>{{$category->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description" class="control-label">@lang('filemanager.description')</label>
                <input id="description" class="form-control" placeholder="@lang('filemanager.category_description_placeholder')" type="text" name="description">
            </div>
            <button class="btn btn-primary hidden" id="btn_submit_category"></button>
            <button class="btn btn-primary hidden" id="btn_submit_category_close"></button>
        </form>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.category.category_inline_js')
@endsection