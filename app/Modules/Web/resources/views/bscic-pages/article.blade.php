@extends('public_home.front')

@section('header-resources')
    <style>
        .parkInfoBody img {
            max-width: 100% !important;
            margin: 5px 0;
            display: block;
        }
    </style>
@endsection

@section ('body')
    <div class="container">
        <div class="singlePageDesign">
            <div class="row">
                <div class="col-md-12">
                    @if(empty($contents))
                        <p class="text-center">No content found.</p>
                    @else

                        {!!  App::isLocale('bn') ? $contents->page_content : $contents->page_content_en !!}
    @endif
</div>
</div>
</div>
</div>
@endsection
