<?php
//$accessMode = ACL::getAccsessRight('settings');
//if (!ACL::isAllowed($accessMode, 'V')) {
//    die('You have no access right! Please contact system admin for more information');
//}
?>

@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
@endsection
@section('content')
    @include('partials.messages')

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong><i class="fa fa-list"></i> API Clients </strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <div class="card ">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="responseMessageDiv"></div>
                                    <div style="font-weight: bold">
                                        URL for Token : <code>{BASE_URL}/d-api/get-token</code> <br>
                                        Authorization : <code>OAuth2</code>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="float-right btn btn-success"
                                            onclick="openAddClientModal(this)"
                                            data-action="{{ url('/') }}">
                                        <i class="fa fa-plus"></i> <b>Add New Client</b>
                                    </button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="clientList"
                                       class="table table-striped table-bordered dt-responsive"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Client name</th>
                                        <th>Token Expiration</th>
                                        <th>Grant Type</th>
                                        <th>Client ID</th>
                                        <th>Secret</th>
                                        <th>Status</th>
                                        <th style="width: 22%;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="apiAssignModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Manage API</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body assigned_api_modal_content">

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md saveAssignedData"><b class="spinner-icon-for-assign"></b> Save</button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="addClientModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

            {!! Form::open(array('url' => '','method' => 'post','id' => 'apiInfoForm','enctype'=>'multipart/form-data',
                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">New Client</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="responseMessageInModalForAddClient"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('client_name') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_name','Client Name',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_name', '', ['class' => 'form-control input-md client_name required']) !!}
                                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('token_expire_time') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('token_expire_time','Token Expire Time (seconds)',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('token_expire_time', '1000', ['class' => 'form-control input-md token_expire_time required']) !!}
                                    {!! $errors->first('token_expire_time','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('grant_type') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('grant_type','Grant Type',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('grant_type', ['client_credentials'=>'client_credentials'],'', ['class' => 'form-control input-md grant_type required']) !!}
                                    {!! $errors->first('grant_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('client_id') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_id','Client ID',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_id', '', ['class' => 'form-control input-md client_id required']) !!}
                                    {!! $errors->first('client_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('client_secret') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_secret','Client Secret',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_secret', '', ['class' => 'form-control input-md client_secret required']) !!}
                                    {!! $errors->first('client_secret','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('allowed_ips') ? 'has-error': ''}}">
                                {!! Form::label('allowed_ips','Allowed IP',['class'=>'col-md-3 required-star','title'=>'Comma separated ip address(Ex:127.0.0.1,127.0.0.2)','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('allowed_ips', '', ['class' => 'form-control input-md required api_allowed_ips','required'=>'true']) !!}
                                    {!! $errors->first('allowed_ips','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md addClientBtn"
                            name="actionBtn"><b class="spinner-icon-for-add-client"></b> Save
                    </button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="editClientModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

            {!! Form::open(array('url' => '','method' => 'post','id' => 'apiInfoForm','enctype'=>'multipart/form-data',
                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Client</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="responseMessageInModalForUpdateClient"></div>
                    <div class="form-group">
                        <div class="row">
                            <input type="hidden" value="" class="encoded_client_id">
                            <div class="col-md-12 {{$errors->has('client_name') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_name','Client Name',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_name', '', ['class' => 'form-control input-md client_name_edit required']) !!}
                                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('token_expire_time') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('token_expire_time','Token Expire Time (seconds)',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('token_expire_time', '1000', ['class' => 'form-control input-md token_expire_time_edit required']) !!}
                                    {!! $errors->first('token_expire_time','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('grant_type') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('grant_type','Grant Type',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('grant_type', ['client_credentials'=>'client_credentials'],'', ['class' => 'form-control input-md grant_type_edit required']) !!}
                                    {!! $errors->first('grant_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('client_id') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_id','Client ID',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_id', '', ['class' => 'form-control input-md client_id_edit required']) !!}
                                    {!! $errors->first('client_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('client_secret') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_secret','Client Secret',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('client_secret', '', ['class' => 'form-control input-md client_secret_edit required']) !!}
                                    {!! $errors->first('client_secret','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-12 {{$errors->has('client_secret') ? 'has-error': ''}}" style="margin-bottom: 6px;">
                                {!! Form::label('client_secret','Client Secret',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('allowed_ips', '', ['class' => 'form-control input-md api_allowed_ips_edit required']) !!}
                                    {!! $errors->first('client_secret','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateClientBtn"
                            name="actionBtn"><b class="spinner-icon-for-update-client"></b> Update
                    </button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>
    <script>
        $(function () {

            getClientList();

            $(document).ready(function () {
                $(".select2_input").select2({
                    tags: true,
                    width: '100%'
                });
            });

        });

        function openBasicInfoEditModal(btn) {
            $('#basicInfoEditModal').modal();
        }


        function getClientList(){
            $('#clientList').DataTable({
                iDisplayLength: 10,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{url("dynamic-api-engine/authentications/get-client-list")}}',
                    method: 'POST',
                    data: function (d) {
                        d.api_id = $('#api_id').val();
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'client_name', name: 'client_name', searchable: true},
                    {data: 'token_expire_time', name: 'token_expire_time', searchable: true},
                    {data: 'grant_type', name: 'grant_type', searchable: true},
                    {data: 'client_id', name: 'client_id', searchable: true},
                    {data: 'client_secret', name: 'client_secret', searchable: true},
                    {data: 'is_active', name: 'is_active', searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        }


        $(document).on("click", ".assignApi", function() {

            var client_id = jQuery(this).data('client_id');

            var btn = $(this);
            btn.prop('disabled', true);
            jQuery(this).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/get-api-map-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_id: client_id
                },
                success: function (response) {
                    if(response.responseCode == 1){
                        btn.prop('disabled', false);
                        $('.spinner-icon').empty();
                        $('.assigned_api_modal_content').html(response.html);
                        $(".select2_input").select2({
                            tags: true,
                            width: '100%'
                        });
                        $('#apiAssignModal').modal();

                    }else{

                    }
                }
            });

        });


        $(document).on("click", ".saveAssignedData", function() {

            var client_id = jQuery('.client_id').val();
            var assign_api  = $('.assign_api').select2("val");

            var btn = $(this);
            btn.prop('disabled', true);
            $('.spinner-icon-for-assign').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/store-api-map-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_id: client_id,
                    assign_api: assign_api
                },
                success: function (response) {
                    if(response.responseCode == 1){
                        btn.prop('disabled', false);
                        $('.spinner-icon-for-assign').empty();

                        $('#apiAssignModal').modal('hide');
                        $('.responseMessageDiv').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');

                    }else{
                        btn.prop('disabled', false);
                        $('.spinner-icon-for-assign').empty();

                        $('.responseMessageInModalForAssignApiInfo').html('<div class="alert alert-danger">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>')
                    }
                }
            });

        });


        function openAddClientModal(btn) {
            $('#addClientModal').modal();
        }


        $(document).on("click", ".addClientBtn", function() {

            var client_name = $('.client_name').val();
            var token_expire_time = $('.token_expire_time').val();
            var grant_type = $('.grant_type').val();
            var client_id = $('.client_id').val();
            var client_secret = $('.client_secret').val();
            var api_allowed_ips = $('.api_allowed_ips').val();

            var btn = $(this);
            btn.prop('disabled', true);
            $('.spinner-icon-for-add-client').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/store-api-client-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_name: client_name,
                    token_expire_time: token_expire_time,
                    grant_type: grant_type,
                    client_id: client_id,
                    api_allowed_ips: api_allowed_ips,
                    client_secret: client_secret
                },
                success: function (response) {
                    if(response.responseCode == 1){
                        btn.prop('disabled', false);
                        $('.spinner-icon-for-add-client').empty();

                        $('#addClientModal').modal('hide');
                        $('.responseMessageDiv').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');
                        var dataTable = $('#clientList').dataTable();
                        dataTable.fnDestroy();
                        getClientList();

                    }else{
                        btn.prop('disabled', false);
                        $('.spinner-icon-for-add-client').empty();

                        $('.responseMessageInModalForAddClient').html('<div class="alert alert-danger">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>')
                    }
                }
            });

        });


        $(document).on("click", ".clientEditModal", function() {

            var client_id = jQuery(this).data('client_id');

            var btn = $(this);
            btn.prop('disabled', true);
            jQuery(this).find('.spinner-icon-edit').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/get-api-client-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_id: client_id
                },
                success: function (response) {
                    if(response.responseCode == 1){
                        btn.prop('disabled', false);
                        $('.spinner-icon-edit').empty();

                        $('.encoded_client_id').val(response.client_id);
                        $('.client_name_edit').val(response.data.client_name);
                        $('.token_expire_time_edit').val(response.data.token_expire_time);
                        $('.grant_type_edit').val(response.data.grant_type);
                        $('.client_id_edit').val(response.data.client_id);
                        $('.client_secret_edit').val(response.data.client_secret);
                        $('.api_allowed_ips_edit').val(response.data.allowed_ips);

                        $('#editClientModal').modal();

                    }else{

                    }
                }
            });

        });


        $(document).on("click", ".updateClientBtn", function() {

            var client_tbl_id = $('.encoded_client_id').val();
            var client_name = $('.client_name_edit').val();
            var token_expire_time = $('.token_expire_time_edit').val();
            var grant_type = $('.grant_type_edit').val();
            var client_id = $('.client_id_edit').val();
            var client_secret = $('.client_secret_edit').val();
            var api_allowed_ips = $('.api_allowed_ips_edit').val();
            $('.responseMessageInModalForUpdateClient').empty();

            var btn = $(this);
            btn.prop('disabled', true);
            $('.spinner-icon-for-update-client').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/update-api-client-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_tbl_id: client_tbl_id,
                    client_name: client_name,
                    token_expire_time: token_expire_time,
                    grant_type: grant_type,
                    client_id: client_id,
                    allowed_ips: api_allowed_ips,
                    client_secret: client_secret
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('.spinner-icon-for-update-client').empty();

                    if(response.responseCode == 1){
                        $('#editClientModal').modal('hide');
                        $('.responseMessageDiv').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');
                        var dataTable = $('#clientList').dataTable();
                        dataTable.fnDestroy();
                        getClientList();

                    }else{
                        $('.responseMessageInModalForUpdateClient').html('<div class="alert alert-danger">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>')
                    }
                }
            });

        });


        $(document).on("click", ".deleteClient", function() {

            if (!confirm('Are you sure, want to delete ?')) {
                return false;
            }

            var client_id = jQuery(this).data('client_id');

            var btn = $(this);
            btn.prop('disabled', true);
            jQuery(this).find('.spinner-icon-delete').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/authentications/delete-api-client-info') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    client_id: client_id
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('.spinner-icon-edit').empty();

                    if(response.responseCode == 1){
                        $('.responseMessageDiv').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');
                        var dataTable = $('#clientList').dataTable();
                        dataTable.fnDestroy();
                        getClientList();
                    }else{

                    }
                }
            });

        });


    </script>
@endsection
