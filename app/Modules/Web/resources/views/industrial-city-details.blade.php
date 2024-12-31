@extends('public_home.front')

@section('header-resources')
@endsection

@section ('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="notice-title"><i class="fa fa-bell" aria-hidden="true"></i>
                            <strong>{{ App::isLocale('bn') ? $industrialData->name : $industrialData->name_en }}</strong></h4>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-12">
                           <span class="col-md-4">
                               <img class="img" src="{{asset($industrialData->image)}}">
                           </span>
                                <p class="potal text-justify"><span style="font-family: bold;font-weight:700;color: #5d5151">{{ App::isLocale('bn') ? $industrialData->details : $industrialData->details_en }}</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
