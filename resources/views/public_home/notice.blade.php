@extends('public_home.front')
@section('body')
    <div style="max-width: 1140px; margin: 16px auto 0 auto; min-height: calc(100vh - 156px);">
        @foreach($notices as $key => $notice)
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapse_{{$loop->index}}">
                        <h6 class="mb-0">
                            {{$notice->heading}}
                        </h6>
                    </div>
                    <div id="collapse_{{$loop->index}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            {!! $notice->details !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
