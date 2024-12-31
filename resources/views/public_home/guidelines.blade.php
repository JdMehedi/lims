@extends('public_home.front')
@section('body')
    <div style="max-width: 1140px; margin: 16px auto 0 auto; min-height: calc(100vh - 156px);">
        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">List of Guidelines</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($guidelines as $key => $guideline)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$guideline->service_name}}</td>
                    <td>
                        @if($guideline->pdf_file_public)
                            <a href="{{$guideline->pdf_file_public}}" target="_blank" class="btn btn-xs btn-success">
                                Open
                            </a>
                        @else
                            <a href="#">No PDF Found</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
