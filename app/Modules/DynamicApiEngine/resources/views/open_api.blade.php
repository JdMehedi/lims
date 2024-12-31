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
    <link rel="stylesheet"
          href="{{ asset("assets/plugins/bootstrap/css/bootstrap4.min.css") }}" />
@endsection
@section('content')
    @include('partials.messages')

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>



    <div class="card card-info border border-info">
        <div class="card-header">
                <h5><strong><i class="fa fa-list"></i> API Basic Information</strong></h5>
        </div>
        <input type="hidden" name="api_id" value="{{ \App\Libraries\Encryption::encodeId($apiListData->id) }}"
               id="api_id"/>
        <div class="card-body">
            <div class="response_message_div_for_api_info"></div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="float-right btn btn-success"
                            onclick="openBasicInfoEditModal(this)"
                            data-action="{{ url('/') }}">
                        <i class="fa fa-edit"></i> <b>Edit </b>
                    </button>

                    <div class="responseMessageDiv"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                    {!! Form::label('name', 'API Name', ['class' => 'col-md-4']) !!}
                    <div class="col-md-8">
                        &nbsp;: <span class="api_name_span">{{$apiListData->name}}
                            @if($apiListData->is_active == 1)
                                <span class="badge badge-primary"><b>Active</b></span>
                            @else
                                <span class="badge badge-danger"><b>In-active</b></span>
                            @endif
                    </span>
                    </div>
                </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('name', 'Request Content Type', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8">
                            &nbsp;: <span class="api_request_content_type_span">{{$apiListData->request_content_type}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('name', 'Method', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8">
                            &nbsp;: <span class="api_method_span">{{$apiListData->method}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {!! Form::label('base_url', 'Access URL', ['class' => 'col-md-2']) !!}
                        <div class="col-md-10">
                            : <span class="api_base_url_span">{{$apiListData->base_url}}</span> <i class="fa fa-copy" title="Copy to Clipboard" data-toggle="tooltip" style="cursor: pointer;" onclick="copyToClipboard(this,'{{$apiListData->base_url}}')"></i>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>


    <div class="card card-info border border-info">
        <div class="card-header">
                <div class="float-left-left">
                    <h5><strong><i class="fa fa-list"></i> API Others Configuration</strong></h5>
                </div>
            </div>
        <div class="card-body">
                <div class="">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#parameterTab" aria-controls="parameterTab"
                               aria-expanded="true">
                                <strong><i class="fas fa-file-text fa-fw"></i> Parameters &nbsp; <span class="badge badge-dark" style="color: white">{{$parameterCounter}}</span></strong>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#operationTab" aria-controls="operationTab"
                               aria-expanded="true">
                                <strong><i class="fas fa-file"></i> Operations  &nbsp; <span class="badge badge-dark" style="color: white">{{$operationCounter}}</span></strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#outputTab" aria-controls="outputTab"
                               aria-expanded="true">
                                <strong><i class="fa fa-file"></i> Outputs  &nbsp; <span class="badge badge-dark" style="color: white">{{$outputCounter}}</span></strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#help" aria-controls="outputTab"
                               aria-expanded="true">
                                <strong><i class="fa fa-info-circle"></i> Help</strong>
                            </a>
                        </li>
                    </ul>

                    <br/>
                    <div class="tab-content">
                        <div id="parameterTab" class="tab-pane fade in active show">
                            @include('DynamicApiEngine::TabContent.parameter_tab')

                        </div>

                        <div id="operationTab" class="tab-pane fade">

                            @include('DynamicApiEngine::TabContent.operation_tab')

                        </div>

                        <div id="outputTab" class="tab-pane fade">

                            @include('DynamicApiEngine::TabContent.output_tab')

                        </div>

                        <div id="help" class="tab-pane fade">

                            @include('DynamicApiEngine::TabContent.help_tab')

                        </div>
                    </div>
                </div>
            </div>

    </div>



    <!-- The Modal -->
    <div class="modal fade" id="basicInfoEditModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            {!! Form::open(array('url' => 'dynamic-api-engine/update-basic-info','method' => 'post','id' => 'apiInfoForm','enctype'=>'multipart/form-data',
                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
            <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">API basic Information Update</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="responseMessageInModalForBaseInfo"></div>
                    <div class="form-group">
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('name') ? 'has-error': ''}}">
                                {!! Form::label('name','Title',['class'=>'col-md-3 required-star','title'=>'Example: User Information','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('name', $apiListData->name, ['class' => 'form-control input-md api_name required']) !!}
                                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('method') ? 'has-error': ''}}">
                                {!! Form::label('method','Method',['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('method', ['GET'=>'GET','POST'=>'POST','PATCH'=>'PATCH'],$apiListData->method, ['class' => 'form-control api_method input-md required']) !!}
                                    {!! $errors->first('method','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('request_content_type') ? 'has-error': ''}}">
                                {!! Form::label('request_content_type','Request Content Type',['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('request_content_type', ['application/x-www-form-urlencoded'=>'application/x-www-form-urlencoded','application/json'=>'application/json','multipart/form-data'=>'multipart/form-data','none'=>'none'],$apiListData->request_content_type, ['class' => 'form-control api_request_content_type input-md required']) !!}
                                    {!! $errors->first('request_content_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('base_url') ? 'has-error': ''}}">
                                {!! Form::label('base_url','API Access URL',['class'=>'col-md-3 required-star','title'=>'Example: http://oss.com.bd/d-api/user-api/get-user-information','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('base_url', $apiListData->base_url, ['class' => 'form-control input-md api_base_url required']) !!}
                                    {!! $errors->first('base_url','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('key') ? 'has-error': ''}}">
                                {!! Form::label('key','API Key',['class'=>'col-md-3 required-star','title'=>'Example: user-api','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('key', $apiListData->key, ['class' => 'form-control input-md api_key required','readonly']) !!}
                                    {!! $errors->first('key','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
{{--                        <div class="row" style="margin-bottom: 6px;">--}}
{{--                            <div class="col-md-12 {{$errors->has('allowed_ips') ? 'has-error': ''}}">--}}
{{--                                {!! Form::label('allowed_ips','Allowed IP',['class'=>'col-md-3 required-star','title'=>'Example: 127.0.0.1','data-toggle'=>'tooltip']) !!}--}}
{{--                                <div class="col-md-9">--}}
{{--                                    {!! Form::text('allowed_ips', $apiListData->allowed_ips , ['class' => 'form-control input-md required api_allowed_ips']) !!}--}}
{{--                                    {!! $errors->first('allowed_ips','<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('allowed_sql_keys') ? 'has-error': ''}}">
                                {!! Form::label('allowed_sql_keys','Allowed SQL Keywords',['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('allowed_sql_keys[]', ['SELECT'=>'SELECT','INSERT'=>'INSERT','UPDATE'=>'UPDATE'],json_decode($apiListData->allowed_sql_keys), ['class' => 'select2_input api_allowed_sql_keys form-control input-md required','multiple'=>'multiple']) !!}
                                    {!! $errors->first('allowed_sql_keys','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('description') ? 'has-error': ''}}">
                                {!! Form::label('description','Description',['class'=>'col-md-3 required-star','title'=>'Example: To get user information of OSS system......','data-toggle'=>'tooltip']) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('description', $apiListData->description, ['class' => 'form-control input-sm api_description required',
                                            'size'=>'5x2', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                                    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 6px;">
                            <div class="col-md-12 {{$errors->has('is_active') ? 'has-error': ''}}">
                                {!! Form::label('is_active','Status',['class'=>'col-md-3 required-star']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('is_active', ['0'=>'In-active','1'=>'Active'],$apiListData->is_active, ['class' => 'form-control api_is_active input-md required']) !!}
                                    {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateApiBasicInfoBtn"
                             name="actionBtn"><span class="edit-spinner-icon"></span> Update
                    </button>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script src="{{ asset("assets/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/sql-formatter.js") }}"></script>
    <script>
        $(function () {

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                const new_tab = e.target.getAttribute('aria-controls') // newly activated tab
                // const previous_tab = e.relatedTarget.getAttribute('aria-controls') // previous active tab
                if (new_tab === 'parameterValidationTab') {
                    loadDocuments();
                }
            });

            /**
             * Document List loading
             * @type {jQuery}
             */

            getParameterList();
            getOperationList();
            getOutputList();

            // $(document).ready(function () {
            //     $(".select2_input").select2({
            //         tags: true,
            //         width: '100%'
            //     });
            //
            // });

        });

        $(document).ready(function () {
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
                $('.api_key').val(path[2]);
            });

        })

        function openBasicInfoEditModal(btn) {
            $('#basicInfoEditModal').modal();
        }

        $(document).on("click", ".updateApiBasicInfoBtn", function() {
            $('.responseMessageInModalForBaseInfo').empty();

            var api_name    = $(".api_name").val();
            var api_id      = $("#api_id").val();
            var api_key     = $(".api_key").val();
            var api_method  = $(".api_method").val();
            var api_request_content_type  = $(".api_request_content_type").val();
            var api_base_url  = $(".api_base_url").val();
       //     var api_allowed_ips  = $(".api_allowed_ips").val();
            var api_allowed_sql_keys  = $('.api_allowed_sql_keys').select2("val");
            var api_description  = $(".api_description").val();
            var api_is_active  = $(".api_is_active").val();

            if(api_name == "" || api_key == "" || api_method == "" || api_request_content_type == "" || api_base_url == "" || api_allowed_sql_keys == "" || api_description == ""){
                alert('Please insert value in required field');
                return false;
            }

            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo e(url('dynamic-api-engine/update-api-basic-info')); ?>",
                data: {
                    _token: $('input[name="_token"]').val(),
                    api_id: api_id,
                    api_name: api_name,
                    api_key: api_key,
                    api_method: api_method,
                    api_request_content_type: api_request_content_type,
                    api_base_url: api_base_url,
                  //  api_allowed_ips: api_allowed_ips,
                    api_allowed_sql_keys: api_allowed_sql_keys,
                    api_description: api_description,
                    api_is_active: api_is_active

                },
                success: function (response) {
                    btn.prop('disabled', false);

                    if(response.responseCode == 1){
                        $('#basicInfoEditModal').modal('hide');
                        $('.response_message_div_for_api_info').html('<div class="alert alert-success">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>');

                        $(".api_name_span").html(api_name);
                        $(".api_base_url_span").html(api_base_url);
                        $(".api_request_content_type_span").html(api_request_content_type);
                        $(".api_method_span").html(api_method);
                        if(api_is_active == 0){
                            $(".api_status_span").html('<span class="label label-danger"><b>In-active</b></span>');
                        }else{
                            $(".api_status_span").html('<span class="label label-info"><b>Active</b></span>');
                        }

                    }else{
                        $('.responseMessageInModalForBaseInfo').html('<div class="alert alert-danger">\n' +
                            '  <strong>'+response.message+'</strong>\n' +
                            '</div>')
                    }
                }
            });
        });


        /*
        #### Parameter related operations
         */
        $(document).on("click", ".saveParameter", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');
            storeParameterName();
        });

        $(document).on("click", ".updateValidation", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');
            updateParameterValidation();
        });

        $(document).on("click", ".updateParameter", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');
            updateParameterName();
        });

        $(document).on('click', '.add_more_parameter_validation', function () {
            var action = $(this).data('action');
            addMoreValidation(action);
        });

        $(document).on('click', '.remove_parameter_validation', function () {
            var action = $(this).data('action');
            if(action == 'insert') {
                $(this).closest('.removeParameter').remove();
            }else{
                $(this).closest('.removeParameterEditSection').remove();
            }
        });


        // $(document).on("change", ".validation_method", function() {
        //     var validation = $(this).find('option:selected').text();
        //     if(validation == "MIN:NUMBER" || validation == "MAX:NUMBER" || validation == "LENGTH:NUMBER" || validation == "LENGTH_BETWEEN:MIN:MAX"){
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').prop('readonly', false);
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').val(0);
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').css("border", "red solid 2px");
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').focus();
        //     }else {
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').prop('readonly', true);
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').val('N/A');
        //         jQuery(this).closest('div.readonlyPointer').find('.validation_method_val').css("border", "#d2d6de solid 1px");
        //     }
        // });

        $(document).on("change", ".edited_validation_method", function() {
            var validation = $(this).find('option:selected').text();
            if(validation == "MIN:NUMBER" || validation == "MAX:NUMBER" || validation == "LENGTH:NUMBER"){
                // jQuery(this).closest('div.readonlyPointerEditSection').find('.edited_validation_method_val').prop('readonly', false);
                // jQuery(this).closest('div.readonlyPointerEditSection').find('.edited_validation_method_val').val(0);
                // jQuery(this).closest('div.readonlyPointerEditSection').find('.edited_validation_method_val').css("border", "red solid 2px");
                // jQuery(this).closest('div.readonlyPointerEditSection').find('.edited_validation_method_val').focus();
                jQuery(this).closest('div.readonlyPointerEditSection').find('.rmv_sql').empty();
                jQuery(this).closest('div.readonlyPointerEditSection').find('.validationValueSection').html('{!! Form::text('edited_validation_method_val[]', '0', ['class' => 'form-control input-md required edited_validation_method_val']) !!}');
                 return true;
            }else if(validation == "LENGTH_BETWEEN:MIN:MAX"){
                jQuery(this).closest('div.readonlyPointerEditSection').find('.rmv_sql').empty();
                jQuery(this).closest('div.readonlyPointerEditSection').find('.validationValueSection').html('{!! Form::text('edited_validation_method_val[]', '0:0', ['class' => 'form-control input-md required edited_validation_method_val fixed_tooltip','title'=>'Example: ','data-toggle'=>'tooltip']) !!}');
                jQuery(this).closest('div.readonlyPointerEditSection').find('.fixed_tooltip').tooltip({placement: 'top',trigger: 'manual'}).tooltip('show');
                return true;
            }else if(validation == "SQL"){
                jQuery(this).closest('div.readonlyPointerEditSection').append('<div class="row rmv_sql" >\n' +
                    '                <div class="col-md-12" style="margin-top: 6px;">\n' +
                    '                    <div class=""></div>\n' +
                    '                    <div class="col-md-11">\n' +
                    '                        {!! Form::textarea('edited_validation_exe_sql[]', '' , ['class' => 'form-control input-sm edited_validation_exe_sql','size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}\n' +
                    '                        {!! $errors->first('sql_validation_message','<span class="help-block">:message</span>') !!}\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>');
                jQuery(this).closest('div.readonlyPointerEditSection').find('.validationValueSection').html('{!! Form::text('edited_validation_method_val[]', 'N/A', ['class' => 'form-control input-md required edited_validation_method_val','style'=>'visibility:hidden']) !!}');
                return true;
            }else {
                jQuery(this).closest('div.readonlyPointerEditSection').find('.rmv_sql').empty();
                jQuery(this).closest('div.readonlyPointerEditSection').find('.validationValueSection').html('{!! Form::text('edited_validation_method_val[]', 'N/A', ['class' => 'form-control input-md required edited_validation_method_val','style'=>'visibility:hidden']) !!}');
            }
        });


        /*
        #### Operation related operations
         */
        $(document).on("click", ".saveOperation", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            storeOperationalData();
        });
        $(document).on("click", ".updateOperation", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            updateOperationalData();
        });


        /*
        #### Outputs related operations
         */
        $(document).on("click", ".saveOutput", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            storeOutputData();
        });
        $(document).on("click", ".updateOutput", function() {
            $(document).find('.spinner-icon').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

            updateOutputlData();
        });


        $(document.body).find(".new_operation_sql_code_beautify_query").on('click',function(){
            $(".operation_exe_sql").val(window.sqlFormatter.format($(".operation_exe_sql").val(),{
                language:'sql',
                uppercase:true
            }));
        });
        $(document.body).find(".edit_operation_sql_code_beautify_query").on('click',function(){
            $(".edit_operation_exe_sql").val(window.sqlFormatter.format($(".edit_operation_exe_sql").val(),{
                language:'sql',
                uppercase:true
            }));
        });
        $(document.body).find(".edit_output_sql_code_beautify_query").on('click',function(){
            $(".edit_output_exe_sql").val(window.sqlFormatter.format($(".edit_output_exe_sql").val(),{
                language:'sql',
                uppercase:true
            }));
        });
        $(document.body).find(".new_output_sql_code_beautify_query").on('click',function(){
            $(".output_exe_sql").val(window.sqlFormatter.format($(".output_exe_sql").val(),{
                language:'sql',
                uppercase:true
            }));
        });


        function copyToClipboard(em,text) {
            var dummy = document.createElement("textarea");
            // to avoid breaking orgain page when copying more words
            // cant copy when adding below this code
            dummy.style.display = 'none'
            document.body.appendChild(dummy);
            //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            $(em).attr('data-original-title', 'Copied')
                .tooltip('fixTitle')
                .tooltip('show');
        }

    </script>
@endsection
