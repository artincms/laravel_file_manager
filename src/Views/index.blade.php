@extends('laravel_file_manager::layouts.master')
@section('page_title','Show Categories')

@section('content')
    <div class="panel panel-headline">
        <div class="row">
            <div class="col-md-3">
                <form action="" method="get" class="search-form" pjax-container="">
                    <div class="input-group input-group-sm ">
                        <input type="text" name="title" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-md-9">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default btn-sm active">
                        <input type="radio" class="grid-view" value="card"><i class="fa fa-th-large"></i>
                    </label>
                    <label class="btn btn-default btn-sm ">
                        <input type="radio" class="grid-view" value="table"><i class="fa fa-list"></i>
                    </label>
                </div>
                <div class="btn-group">
                    <a href="{{route('LFM.FileUpload')}}" class="btn btn-sm btn-success">
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;upload
                    </a>
                </div>

                <a class="btn btn-sm btn-success" title="create new category" href="{{route('LFM.ShowCategories.Create')}}">
                    <i class="fa fa-folder"></i>&nbsp;&nbsp;Cat
                </a>


                <a class="btn btn-sm btn-primary grid-trash-o"><i class="fa fa-trash-o"></i></a>
                <a class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-refresh"></i></a>


            </div>

        </div>
        <div class="panel-body">
            @include('laravel_file_manager::content')
        </div>
    </div>
@endsection

