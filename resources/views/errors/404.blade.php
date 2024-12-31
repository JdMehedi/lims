@extends('public_home.front')
@section('header-resources')
    <style>
        .content-404 {
            text-align: center;
            padding: 50px 0px 0px 0px;
        }

        .content-404 p {
            margin: 18px 0px 45px 0px;
            font-size: 2em;
            color: #666;
            text-align: center;
        }

        .content-404 img {
            margin: 0 auto;
        }
    </style>
@endsection
@section ('body')

    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="content-404">
                    <img src="{{url('assets/images/coming_soon.webp') }}" title="error" id="img"
                         class="img-responsive"/>
                    <p>{{ $exception->getMessage() }}</p>
                    <p>
                        <br/>Page not found [404]<br/>
                        <a href="{{ url()->previous() }}" class="btn btn-success">Go back</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
