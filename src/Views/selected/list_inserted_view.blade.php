@extends('laravel_file_manager::layouts.master')
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/view_insert.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        @php( $i =0)
        <ul class="">
            @foreach($datas as $data)
                <li><a href="{{$data['full_url']}}">{{$data['file']['name']}}</a>
                    <a href="" class="grid-row-delete pull-left myicon" id="trash_insert" data-type="file" data-section="{{$section}}" data-id="{{$data['file']['id']}}">
                        <i class="fa fa-trash"></i>
                    </a>
                </li>
                @php($i = $i+1)
            @endforeach
        </ul>
    </div>

@endsection