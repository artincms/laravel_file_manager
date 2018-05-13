@extends('laravel_file_manager::layouts.master')
@section('add_js')
    <script type="text/javascript" src="{{URL::asset('vendor/laravel_file_manager/js/view_insert.js')}}"></script>
@endsection
@section('content')
    <div class="container-fluid">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Row</th>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php( $i =0)
            @foreach($datas as $data)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$data['file']['id']}}</td>
                    <td><a href="{{$data['full_url']}}">{{$data['file']['name']}}</a></td>
                    <td>
                        <a href="" class="grid-row-delete pull-right myicon" id="trash_insert" data-type="file" data-section="{{$section}}" data-id="{{$data['file']['id']}}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
