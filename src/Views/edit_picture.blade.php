@extends('laravel_file_manager::layouts.master')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/css/croppie.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/croppie.min.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#orginal_tab">Orginal</a></li>
            <li><a data-toggle="tab" href="#large_tab">Large</a></li>
            <li><a data-toggle="tab" href="#medium_tab">Medium</a></li>
            <li><a data-toggle="tab" href="#small_tab">Small</a></li>
        </ul>
        <div class="tab-content">
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
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.picture.edit_picture_inline_js')
@endsection