@extends('laravel_file_manager::layouts.master')
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/view_insert.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        @php( $i =0)
        @foreach($datas as $data)
            <div class="btn-group small_inserted_view col-md-6 pull-left margin-top-1">
                <a href="" class="grid-row-delete pull-left myicon col-md-1" id="trash_insert" data-type="file" data-section="{{$section}}" data-id="{{$data['file']['id']}}">
                    <i class="fa fa-trash"></i>
                </a>
                <div type="text" class="col-md-8" placeholder=""><a href="{{$data['full_url']}}">{{$data['file']['name']}}</a></div>
                <div class="col-md-2 pull-right">{{$data['file']['size']}}</div>
            </div>
        @endforeach
    </div>
@endsection