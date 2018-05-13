@extends('laravel_file_manager::layouts.upload')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/css/bootstrap-tabs-x.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/croppie.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel_file_manager/css/sweetalert2.min.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/croppie.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/sweetalert2.min.js')}}"></script>
@endsection
@section('content')
    <div id="tabs-container" class="align-center">
        <div class='tabs-x tabs-above tab-bordered tabs-krajee'>
            <ul id="myTab-tabs-above" class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#orginal_tab" role="tab" data-toggle="tab" data-url="">Orginal</a></li>
                <li id="large_nav"><a href="#large_tab" role="tab-kv" data-toggle="tab" data-url="">Large</a></li>
                <li ><a href="#medium_tab" role="tab-kv" data-toggle="tab" data-url="">Medium</a></li>
                <li><a href="#small_tab" role="tab-kv" data-toggle="tab" data-url="">Small</a></li>
            </ul>
            <div id="myTabContent-tabs-above" class="tab-content">
                <div class="tab-pane fade in active" id="orginal_tab">
                    <div class="crop_orginal" id="image_orginal">
                        <img id="img_orginal" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'orginal'])}}">
                    </div>
                </div>
                <div class="tab-pane fade " id="large_tab">
                    <div class="crop_large" id = "image_large">
                        <img id="img_large" src="{{route('LFM.DownloadFile',['type' => 'ID','id'=> $file->id,'size_type' => 'large'])}}">
                    </div>
                </div>
                <div class="tab-pane fade" id="medium_tab">
                    <div class="crop_medium" id="image_medium">
                    </div>
                </div>
                <div class="tab-pane fade" id="small_tab">
                    <div class="crop_small" id="image_small">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.picture.edit_picture_inline_js')
@endsection