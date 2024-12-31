<style>
    .custom_error {
        outline: 1px solid red;
    }
</style>
@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
@endsection
@section('body')
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-success border border-success">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong><i class="fa fa-list" style="margin-right: 10px;"></i>Bidding License Configuration</strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    {!! Form::open(array('url' => '/settings/update-bidding-license-configuration/'.\App\Libraries\Encryption::encodeId($bidding_license->id),'method' => 'post', 'class' => 'form-horizontal','id'=>'biddingLicenseConfiguration',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('license_name', 'License Name', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{$errors->has('license_name') ? 'has-error': ''}}">
                                    {!! Form::select('license_name',$list_of_bidding_license,$bidding_license->module_name,['class'=>'form-control col-md-4 required', 'id' => 'license_name', "required"]) !!}
                                    {!! $errors->first('license_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('start_date', 'Start Date', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8">
                                    {{ Form::date('start_date', $bidding_license->start_date, ['class' => 'form-control col-md-4', 'placeholder' => 'Application start date', 'id' =>'start_date']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('end_date', 'End Date', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8">
                                    {{ Form::date('end_date', $bidding_license->end_date, ['class' => 'form-control col-md-4', 'placeholder' => 'Application end date', 'id' =>'end_date']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" id="submitBtn" class="btn btn-lg btn-success" value="Update"/>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script>

    </script>
@endsection




