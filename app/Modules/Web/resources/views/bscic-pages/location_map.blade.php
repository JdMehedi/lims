@extends('public_home.front')

@section('header-resources')

@endsection

@section ('body')

    <div class="container">
        <div class="singlePageDesign">
            <div class="row">
                <div class="col-md-12 text-justify">
                    <div class="location_map" style="max-height: 600px">
                        {!! (!empty($industrialMap->body)?$industrialMap->body:'') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')

@endsection
