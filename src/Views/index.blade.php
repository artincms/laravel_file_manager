@extends('laravel_file_manager::layouts.master')
@section('page_title','Show Categories')
@section('content')
    <div class="col-md-2">
        <div class="show_cat_foolder">
            <div class="top_js_tree_folder link_to_category pointer" id="share_category" data-id="{{LFM_getEncodeId(-2)}}"><i class="fa fa-folder"></i><span class="show_top_folder_name">@lang('filemanager.share_folder')</span></div>
            <div id="js_tree_share_div">
                <div id="jstree_category_share"></div>
            </div>
            <hr/>
            <div class="top_js_tree_folder link_to_category pointer" data-id="{{LFM_getEncodeId(-1)}}" id="public_category"><i class="fa fa-folder"></i><span class="show_top_folder_name">@lang('filemanager.public_folder')</span></div>
            <div id="js_tree_public_div">
                <div id="jstree_category_public"></div>
            </div>
            <hr/>
            <div class="top_js_tree_folder link_to_category pointer" id="media_category" data-id="{{LFM_getEncodeId(0)}}"><i class="fa fa-folder-open"></i><span class="show_top_folder_name">@lang('filemanager.root_folder')</span></div>
            <div id="js_tree_root_div">
                <div id="jstree_category_root"></div>
            </div>
        </div>
        <hr/>

    </div>
    <div class="col-md-10">
        <div class="panel panel-headline">
            <div class="panel-heading background_define">
                <div class="row">
                    <div class="col-md-9 col-sm-9">
                        <div class="btn-group float-left" data-toggle="buttons">
                            <label class="btn btn-default btn-sm">
                                <a href="" id="select_all" class="selectbox">@lang('filemanager.select_all')</a>
                            </label>
                            <label class="btn btn-sm btn-default selectbox ">
                                <a href="" id="select_none" class="selectbox">@lang('filemanager.none')</a>
                            </label>
                        </div>
                        <div class="btn-group float-left margin-left-1" data-toggle="buttons">
                            <label class="btn btn-default btn-sm display active" id="show_grid">
                                <i class="fa fa-th-large"></i>
                            </label>
                            <label class="btn btn-default btn-sm display" id="show_list" data-id="0">
                                <i class="fa fa-list"></i>
                            </label>
                        </div>
                        <label href="{{route('LFM.FileUpload' , ['category_id' =>LFM_getEncodeId(0) , 'callback'=> LFM_CheckFalseString($callback),'section'=>LFM_CheckFalseString($section)])}}" class="btn btn-sm btn-success uploadfile float-left margin-left-1"
                               data-toggle="modal"
                               data-target="#create_upload_modal">
                            <i class="fa fa-upload"></i>&nbsp;&nbsp;@lang('filemanager.upload')
                        </label>
                        <div class="btn-group float-left margin-left-1" data-toggle="buttons">
                            <label class="btn btn-sm btn-success create_category" title="create new category"
                                   href="{{route('LFM.ShowCategories.Create',['category_id' => LFM_getEncodeId(0), 'callback' => LFM_CheckFalseString($callback) , 'section' => LFM_CheckFalseString($section)])}}" data-toggle="modal"
                                   data-target="#create_category_modal">
                                <i class="fa fa-folder"></i>&nbsp;&nbsp;@lang('filemanager.cat')
                            </label>
                        </div>
                        <label class="btn btn-sm margin-left-1 btn-primary grid-trash-o float-left" id="bulk_delete"><i class="fa fa-trash-o"></i></label>
                        <label class="btn btn-sm btn-primary grid-refresh float-left margin-left-1" id="refresh_page" data-id="{{LFM_getEncodeId(0)}}" data-type="grid" data-category-name="media" data-section="{{LFM_CheckFalseString($section)}}"
                               data-callback="{{LFM_CheckFalseString($callback)}}" data-category-type="media"><i class="fa fa-refresh"></i></label>
                        <div class="btn-group float-left margin-left-1" data-toggle="buttons">
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
                                <input type="text" id="search_media" name="title" class="form-control" placeholder="@lang('filemanager.search_placeholder')">
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
    </div>
    @include('laravel_file_manager::helpers.index.Index_modal_creator')
@endsection
@section('javascript')
    @include('laravel_file_manager::helpers.index.Index_inline_js')
@endsection