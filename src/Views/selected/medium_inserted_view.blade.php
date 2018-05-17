@extends('laravel_file_manager::layouts.master')
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/view_insert.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <ul class="media-content clearfix col-md-12 insert_thumb">
            @foreach($datas as $data)
                <li class="list_style_none center col-md-4 inserted_tumb">
                    <div class="media-attachment-info">
                        <div class="insert_thumb_action">
                            <a href="" class="grid-row-delete pull-right myicon" id="trash_insert" data-type="file" data-section="{{$section}}" data-id="{{$data['file']['id']}}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                        <div class="show_image">
                            @if($data['file']['icon'] == 'image')
                                <a href="{{$data['full_url']}}"><img id="insetred_thumb_img" src="{{$data['full_url']}}"></a>
                            @else
                                <a href="{{$data['full_url']}}"><i class="fa icon_thumb {{$data['file']['icon']}} img-file-thumbnail"></i></a>
                            @endif
                        </div>
                        <div class="insert_thumb_info center">
                            {{$data['file']['name']}}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection