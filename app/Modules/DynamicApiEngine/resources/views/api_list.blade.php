<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
@endsection
@section('content')
{{--    @include('partials.messages')--}}
    <div class="col-md-12 api_operation_msg"></div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong><i class="fa fa-list"></i> API List</strong></h5>
                    </div>
                    <div class="float-right">
                    @if(ACL::getAccsessRight('settings','E'))
                        <button type="button" class="pull-right btn btn-default"
                                onclick="openModal(this)"
                                data-action="{{ url('/') }}" style="margin-bottom: 10px;">
                            <i class="fa fa-plus"></i> <b>New API </b>
                        </button>
                    @endif
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">


                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="dapi_list"
                               class="table table-striped table-bordered dt-responsive"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>API name</th>
                                <th>Method</th>
                                <th>Access URL</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <!-- The Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            {!! Form::open(array('url' => 'dynamic-api-engine/store-basic-info','method' => 'post','id' => 'apiAddInfoForm','enctype'=>'multipart/form-data',
                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
            <!-- Modal Header -->
                <div class="modal-header">
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible" style="margin-left=10px; margin-right=10px;">
                     <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
                    <h4 class="modal-title">New API Basic Information</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('name') ? 'has-error': ''}}">
                                {!! Form::label('name','Title',['class'=>'col-md-3 required-star','title'=>'Example: User Information API','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('name', '', ['class' => 'form-control input-md required api_name','required'=>'true']) !!}
                                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('method') ? 'has-error': ''}}">
                                {!! Form::label('method','Method',['class'=>'col-md-3 required-star','title'=>'Api request method','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('method', ['GET'=>'GET','POST'=>'POST','PATCH'=>'PATCH'],'', ['class' => 'form-control input-md required api_method','required'=>'true']) !!}
                                    {!! $errors->first('method','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('request_content_type') ? 'has-error': ''}}">
                                {!! Form::label('request_content_type','Request Content Type',['class'=>'col-md-3 required-star','title'=>'Api request body content type','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('request_content_type', ['application/x-www-form-urlencoded'=>'application/x-www-form-urlencoded','application/json'=>'application/json','multipart/form-data'=>'multipart/form-data','none'=>'none'],'', ['class' => 'form-control input-md required api_request_content_type','required'=>'true']) !!}
                                    {!! $errors->first('request_content_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('base_url') ? 'has-error': ''}}">
                                {!! Form::label('base_url','API Access URL',['class'=>'col-md-3 required-star','title'=>'Example: http://oss.com.bd/d-api/user-api/get-user-information','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('base_url', '', ['class' => 'form-control input-md required api_base_url','required'=>'true']) !!}
                                    {!! $errors->first('base_url','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('key') ? 'has-error': ''}}">
                                {!! Form::label('key','API Key',['class'=>'col-md-3 required-star','title'=>'Example: user-api','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('key', '', ['class' => 'form-control input-md required api_key','readonly','required'=>'true']) !!}
                                    {!! $errors->first('key','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

{{--                        <div class="row" style="margin-bottom: 6px;">--}}
{{--                            <div class="col-md-12 {{$errors->has('allowed_ips') ? 'has-error': ''}}">--}}
{{--                                {!! Form::label('allowed_ips','Allowed IP',['class'=>'col-md-3 required-star','title'=>'Comma separated ip address(Ex:127.0.0.1,127.0.0.2)','data-toggle'=>'tooltip']) !!}--}}
{{--                                <div class="col-md-9">--}}
{{--                                    {!! Form::text('allowed_ips', '', ['class' => 'form-control input-md required api_allowed_ips','required'=>'true']) !!}--}}
{{--                                    {!! $errors->first('allowed_ips','<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}



                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('allowed_sql_keys') ? 'has-error': ''}}">
                                {!! Form::label('allowed_sql_keys','Allowed SQL Keywords',['class'=>'col-md-3 required-star','title'=>'Allowed sql query operation types','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('allowed_sql_keys[]', ['SELECT'=>'SELECT','INSERT'=>'INSERT','UPDATE'=>'UPDATE'],'', ['class' => 'select2_input form-control input-md required api_allowed_sql_keys','multiple'=>'multiple','required'=>'true']) !!}
                                    {!! $errors->first('allowed_sql_keys','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('description') ? 'has-error': ''}}">
                                {!! Form::label('description','Description',['class'=>'col-md-3 required-star','title'=>'Example: API to get user information','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('description', null, ['class' => 'form-control input-sm required api_description',
                                            'size'=>'5x2', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240",'required'=>'true']) !!}
                                    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="display: block;">
                    <div class="float-left">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <div class="float-right">
                        <button type="submit" class="btn btn-secondary saveBtnAction" ><span class="submitBtnVal">Submit</span></button>
                    </div>
                    <div class="clearfix"></div>

{{--                    <input onclick="saveBtnAction()" type="submit" value="Save" id="submit_btn" class="btn btn-info btn-md"></input>--}}
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>

    @if(Session::has('error'))
        <script>
            $('#myModal').modal();
        </script>
    @endif

    <script>

        function openModal(btn) {
            $('#myModal').modal();
        }



        $(".saveBtnAction").on('click', function (e) {
            e.preventDefault();

            var api_name    = $(".api_name").val();
            var api_key     = $(".api_key").val();
            var api_method  = $(".api_method").val();
            var api_request_content_type  = $(".api_request_content_type").val();
            var api_base_url  = $(".api_base_url").val();
          //  var api_allowed_ips  = $(".api_allowed_ips").val();
            var api_allowed_sql_keys  = $('.api_allowed_sql_keys').select2("val");
            var api_description  = $(".api_description").val();

            var url_slug = api_base_url.replace(/^https?:\/\//, '').split('/');

            if(api_name == "" || api_key == "" || api_method == "" || api_request_content_type == "" || api_base_url == "" || api_allowed_sql_keys == "" || api_description == ""){
                alert('All fields are required');
                return false;
            }

            if(url_slug.length < 4){
                $('.api_base_url').addClass('error');
                alert('Not valid API Access URL');
                return false;
            };

            $('.submitBtnVal').val("Processing ....");

            $('#apiAddInfoForm').submit();
        });


        $(document).ready(function () {

            function copyToClipboard(em,text) {
                var dummy = document.createElement("textarea");
                dummy.style.display = 'none'
                document.body.appendChild(dummy);
                dummy.value = text;
                dummy.select();
                document.execCommand("copy");
                document.body.removeChild(dummy);
                $(em).attr('data-original-title', 'Copied')
                    .tooltip('fixTitle')
                    .tooltip('show');
            }

            function getApiList() {

                $('#dapi_list').DataTable({
                    iDisplayLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: '{{url("dynamic-api-engine/get-list")}}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name', searchable: true},
                        {data: 'method', name: 'method', searchable: true},
                        {data: 'base_url', name: 'base_url', searchable: true},
                        {data: 'is_active', name: 'is_active', searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    "aaSorting": []
                });

             //   copyToClipboard(em,text);
            }

            getApiList();


            $(document).ready(function () {
                $(".select2_input").select2({
                    tags: true,
                    width: '100%'
                });
            });


            $('[data-toggle="tooltip"]').tooltip();


            $("#base_url").on('keyup', function () {
                var strUrl = $(this).val();
                var path = strUrl.replace(/^https?:\/\//, '').split('/');
                $("#key").val(path[2]);
            });


            $(document).on("click", ".fa-copy", function() {
                const text =  $(this).prev().text();
                console.log(text)
                copyToClipboard(this, $(this).prev().text());
            })


            $(document).on("click", ".deleteAPI", function() {

            if (!confirm('Are you sure, want to delete ?')) {
                return false;
            }

            var api_id = jQuery(this).data('api_id');

            var btn = $(this);
            btn.prop('disabled', true);
            jQuery(this).find('.spinner-icon-delete').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ url('dynamic-api-engine/delete-api') }}",
                data: {
                    _token: $('input[name="_token"]').val(),
                    api_id: api_id
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('.spinner-icon-delete').empty();

                    if(response.responseCode == 1){
                        $('.api_operation_msg').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');
                        var dataTable = $('#dapi_list').dataTable();
                        dataTable.fnDestroy();
                        getApiList();
                    }else{

                    }
                }
            });

        });

    });

    </script>
@endsection
