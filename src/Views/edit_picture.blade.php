@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/css/build/croppie.min.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/build/croppie.min.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="tab-content" id="show_edit_picture">
                <div class="crop_original" id="image_original">
                    <img id="img_original" src="{{LFM_GenerateDownloadLink('ID',$file->id,'original')}}">
                </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.picture.edit_picture_inline_js')
@endsection