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
                    {!! Form::open(array('url' => '/settings/store-bidding-license-configuration','method' => 'post', 'class' => 'form-horizontal','id'=>'biddingLicenseConfiguration',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('license_name', 'License Name', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{$errors->has('license_name') ? 'has-error': ''}}">
                                    {!! Form::select('license_name',$bidding_license_arr,'',['class'=>'form-control col-md-4 required', 'id' => 'license_name', "required"]) !!}
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
                                   {{ Form::date('start_date', '', ['class' => 'form-control col-md-4', 'placeholder' => 'Application start date', 'id' =>'start_date']) }}
                               </div>
                           </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('end_date', 'End Date', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8">
                                    {{ Form::date('end_date', '', ['class' => 'form-control col-md-4', 'placeholder' => 'Application end date', 'id' =>'end_date']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" id="submitBtn" class="btn btn-lg btn-success" value="Submit"/>
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
                        <h5><strong>Bidding Configured License List</strong></h5>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>License Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script>

        // document.getElementById('submitBtn').addEventListener('click', function(e){
        //     e.preventDefault()
        //     let validated = true;
        //     let license_name = document.getElementById('license_name').value;
        //     if(!license_name || license_name === null || typeof license_name === 'undefined'){
        //         document.getElementById('license_name').classList.add('custom_error')
        //         // return
        //         validated = false;
        //     }
        //
        //     let fileInput = document.getElementById('bulk_file');
        //     if (fileInput.value === '') {
        //         document.getElementById('bulk_file').classList.add('custom_error')
        //         // return
        //         validated = false;
        //     }
        //     if(validated) {
        //         document.getElementById('bulkDataUpload').submit()
        //     }
        // });
        //
        // document.getElementById('license_name').addEventListener('change', function(){
        //     if(document.getElementById('license_name').classList.contains('custom_error')){
        //         document.getElementById('license_name').classList.remove('custom_error');
        //     }
        // })
        //
        // document.getElementById('bulk_file').addEventListener('change', function(){
        //     if(document.getElementById('bulk_file').classList.contains('custom_error')){
        //         document.getElementById('bulk_file').classList.remove('custom_error');
        //     }
        // })


        $(function () {

            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/get-bidding-configured-license-list")}}',
                    method: 'GET',
                },
                columns: [
                    {data: 'module_name'},
                    {data: 'start_date'},
                    {data: 'end_date'},
                    {data: 'action'},
                ],
                "aaSorting": []
            });

        });

    </script>
@endsection




