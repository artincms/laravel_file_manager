@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/css/croppie.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/croppie.min.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <ul class="nav nav-pills">
            <li class="nav-item active" active id="tab_img_orginal">
                <a class="nav-link" data-toggle="tab" href="#orginal_tab">Orginal Image</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_img_large" data-toggle="tab" href="#large_tab" role="tab">Large Image</a>
            </li>
            <li class="nav-item" id="tab_img_meidum">
                <a class="nav-link" data-toggle="tab" href="#medium_tab">Medium Image</a>
            </li>
            <li class="nav-item" id="tab_img_small">
                <a class="nav-link" data-toggle="tab" href="#small_tab">Small Image</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane container active bg-light" id="orginal_tab">
                <div class="crop_orginal" id="image_orginal">
                    <img id="img_orginal" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'orginal'])}}">
                </div>
            </div>
            <div class="tab-pane container fade bg-light" id="large_tab">
                <div class="crop_large" id = "image_large">
                    <img id="img_large" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'large'])}}">
                </div>
            </div>
            <div class="tab-pane container fade bg-light" id="medium_tab">
                <div class="crop_medium" id="image_medium">
                    <img id="img_medium" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'medium'])}}">
                </div>
            </div>
            <div class="tab-pane container fade bg-light" id="small_tab">
                <div class="crop_small" id="image_small">
                    <img id="img_small" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'small'])}}">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.picture.edit_picture_inline_js')
@endsection