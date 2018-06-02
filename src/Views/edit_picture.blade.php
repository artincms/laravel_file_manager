@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/css/croppie.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/croppie.min.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="tab-content" id="show_edit_picture">
                <div class="crop_orginal" id="image_orginal">
                    <img id="img_orginal" src="{{LFM_GenerateDownloadLink('ID',$file->id,'orginal')}}">
                </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.picture.edit_picture_inline_js')
@endsection