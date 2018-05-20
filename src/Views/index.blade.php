@extends('laravel_file_manager::layouts.master')
@section('page_title','Show Categories')
@section('add_css')
    <link href="{{ asset('vendor/laravel_file_manager/packages/datatabels/datatables.min.css') }}" rel="stylesheet">
@endsection
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/FullScreenView.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/packages/datatabels/datatables.min.js')}}"></script>

@endsection
@section('content')
    <div class="panel panel-headline">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-9 col-sm-9">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default btn-sm">
                            <a href="" id="select_all" class="selectbox">select all</a>
                        </label>
                        <label class="btn btn-sm btn-default selectbox ">
                            <a href="" id="select_none" class="selectbox">none</a>
                        </label>
                    </div>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default btn-sm display active" id="show_grid">
                            <i class="fa fa-th-large"></i>
                        </label>
                        <label class="btn btn-default btn-sm display" id="show_list" data-id="0">
                            <i class="fa fa-list"></i>
                        </label>
                    </div>
                    <label href="{{route('LFM.FileUpload' , ['category_id' =>0 , 'callback'=> LFM_CheckFalseString($callback),'section'=>LFM_CheckFalseString($section)])}}" class="btn btn-sm btn-success uploadfile" data-toggle="modal"
                           data-target="#create_upload_modal">
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;upload
                    </label>
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-sm btn-success create_category" title="create new category"
                               href="{{route('LFM.ShowCategories.Create',['category_id' => 0, 'callback' => LFM_CheckFalseString($callback) , 'section' => LFM_CheckFalseString($section)])}}" data-toggle="modal" data-target="#create_category_modal">
                            <i class="fa fa-folder"></i>&nbsp;&nbsp;Cat
                        </label>
                    </div>
                    <label class="btn btn-sm btn-primary grid-trash-o" id="bulk_delete"><i class="fa fa-trash-o"></i></label>
                    <label class="btn btn-sm btn-primary grid-refresh" id="refresh_page" data-id="0" data-type="grid" data-category-name="media" data-section="{{LFM_CheckFalseString($section)}}"
                           data-callback="{{LFM_CheckFalseString($callback)}}"><i class="fa fa-refresh"></i></label>
                    <div class="btn-group" data-toggle="buttons">
                        @if($insert == 'insert')
                            <label class="btn btn-default btn-sm display" id="insert_file" data-value=0>
                                <i class="fa fa-share-square-o"><span>insert</span>
                                </i>
                            </label>
                            <label class="btn-secondary btn-default" id="show_selected_item"></label>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <form action="" method="get" class="search-form" pjax-container="">
                        <div class="input-group input-group-sm ">
                            <input type="text" id="search_media" name="title" class="form-control" placeholder="Search...">
                            <span class="input-group-append">
                            <button type="submit" name="search" id="search-btn" data-target-search="search_media" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="panel-body">
            @include('laravel_file_manager::content')
        </div>
    </div>
    {{--add modal Create  section--}}
    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="modal_iframe"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create_modal_button">Submit Form</button>
                </div>
            </div>
        </div>
    </div>
    {{--END Create  Modal--}}

    {{--add modal Create Upload section--}}
    <div class="modal fade" id="create_upload_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal_dialog_upload" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"  id="modal_upload_div" >
                    <div class="embed-responsive embed-responsive-16by9 modal_iframe_parent">
                        <iframe class="modal_iframe" id="create_upload_modal_iframe"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--END Create Upload Modal--}}

    {{--add modal Create  category--}}
    <div class="modal fade" id="create_category_modal" tabindex="-1" role="dialog" aria-labelledby="create_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal_dialog_category" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body category_upload_body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="modal_iframe create_category_modal_iframe" id="modal_iframe_category"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create_category_modal_button">Submit Form</button>
                </div>
            </div>
        </div>
    </div>
    {{--END Create  Modal--}}

@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.index.Index_inline_js')
@endsection