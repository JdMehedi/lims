<style>
    .custom_error {
        outline: 1px solid red;
    }
</style>
@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
@endsection
@php
    $modules = DB::table('process_type')->where('status', 1)->pluck('drop_down_label', 'id');
@endphp
@section('content')
<style>
    .content-wrapper {
        height: auto;
    }
</style>
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
                        <h5><strong><i class="fa fa-list" style="margin-right: 10px;"></i>Bulk License data upload</strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    {!! Form::open(array('url' => '/settings/upload-bulk-xls','method' => 'post', 'class' => 'form-horizontal','id'=>'bulkDataUpload',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('license_name', 'License Name', ['class' => 'col-md-4 required-star', 'style'=>'text-align:center']) !!}
                                <div class="col-md-8 {{$errors->has('license_name') ? 'has-error': ''}}">
                                    {!! Form::select('license_name', ['0' =>'Select One'] + $modules->toArray(),'',['class'=>'form-control col-md-4 required', 'id' => 'license_name', "required"]) !!}
                                    {!! $errors->first('license_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group row" style="margin-bottom:0px!important;">
                                {!! Form::label('bulk_file', 'Upload bulk file(XLS)', ['class' => 'col-md-4 required-star', 'style'=>'text-align:center']) !!}
                                <div
                                    class="col-md-8 {{ $errors->has('bulk_file') ? 'has-error' : '' }}">
                                    <div class="row"
                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                        <div class="col-md-4">
                                            <input type="file"
                                                   style="border: none; margin-bottom: 5px;"
                                                    required
                                                   name="bulk_file"
                                                   id="bulk_file"
                                                   size="300x300"/>
                                                   <span class="text-success"
                                                    style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: XLS]
                                                       <a href="{{asset('/assets/sample_bulk_data.xlsx')}}" class="btn btn-success" download>Sample File</a>
                                                   </span>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="submit" id="submitBtn" class="btn btn-success lodgingSubmit" value="Upload"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">

{{--                        <input type="submit" id="submitBtn" class="btn btn-lg btn-success lodgingSubmit" value="Upload"/>--}}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong>Uploaded Bulk data</strong></h5>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="card-body">
                    <div class="table-responsive" style="overflow: auto;">
                        <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Office Address</th>
                                <th>Tracking Number</th>
                                <th>License Number</th>
                                <th>Issue Date</th>
                                <th>Expire Date</th>
                                <th>Contact Name</th>
                                <th>Contact Designation</th>
                                <th>Contact Email</th>
                                <th>Contact Mobile</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script>
        // $('.lodgingSubmit').on('click', function(e){
        //     console.log(11)
        //     $(this).attr('disabled');
        //     $(this).prepend(`<div class="spinner">
        //                     <div class="spinner-border"></div>
        //                     Loading..
        //                 </div>`)
        //
        // });
        document.getElementById('submitBtn').addEventListener('click', function(e){
            e.preventDefault()
            let validated = true;
            let license_name = document.getElementById('license_name').value;
            if(!license_name || license_name === null || typeof license_name === 'undefined'){
                document.getElementById('license_name').classList.add('custom_error')
                // return
                validated = false;
            }

            let fileInput = document.getElementById('bulk_file');
            if (fileInput.value === '') {
                document.getElementById('bulk_file').classList.add('custom_error')
                // return
                validated = false;
            }
            if(validated) {
                $(this).attr('disabled', 'disabled');
                $(`<div class="spinner">
                <div class="spinner-border"></div>
                Loading..
            </div>`).insertBefore(this);
                document.getElementById('bulkDataUpload').submit()
            }
        });

        document.getElementById('license_name').addEventListener('change', function(){
            if(document.getElementById('license_name').classList.contains('custom_error')){
                document.getElementById('license_name').classList.remove('custom_error');
            }
        })

        document.getElementById('bulk_file').addEventListener('change', function(){
            if(document.getElementById('bulk_file').classList.contains('custom_error')){
                document.getElementById('bulk_file').classList.remove('custom_error');
            }
        })


        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/get-bulk-upload-data")}}',
                    method: 'GET',
                },
                columns: [
                    {data: 'company_name'},
                    {data: 'reg_office_address'},
                    {data: 'tracking_no'},
                    {data: 'license_no'},
                    {data: 'license_issue_date'},
                    {data: 'expiry_date'},
                    {data: 'designation'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'mobile'},
                ],
                "aaSorting": []
            });

        });

    </script>
@endsection




