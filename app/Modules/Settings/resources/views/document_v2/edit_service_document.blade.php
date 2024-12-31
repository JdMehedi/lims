<style>
    .select2-container{
        width: 100% !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">

{!! Form::open(array('url' => url('/settings/document-v2/service/update/' . $encoded_service_doc_id),'method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'documentCreateForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
<div class="modal-header">

    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-stop"></i> Edit Document for Services</h4>
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
    </button>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('doc_id','Document name: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-9" style="width: 100% !important;">
                    {!! Form::select('doc_id', $document_list, $service_doc_info->doc_id, ['class'=>'form-control required input-sm','placeholder'=>'Document name']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('process_type_id','Process type: ',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-9">
                    {!! Form::select('process_type_id', $process_list, $service_doc_info->process_type_id	, ['class'=>'form-control required input-sm','placeholder'=>'Select process type', 'onchange' => "getAttachmentType(this.value, 'doc_type_for_service_id', ". $service_doc_info->doc_type_for_service_id .")"]) !!}
                </div>
            </div>
        </div>
    </div>


    <div id="result"></div>
{{--    <div class="row">--}}
{{--        <div class="col-lg-10">--}}
{{--            <div class="form-group col-md-12">--}}
{{--                {!! Form::label('doc_type_for_service_id','Document type: ',['class'=>'col-md-3']) !!}--}}
{{--                <div class="col-md-9">--}}
{{--                    {!! Form::select('doc_type_for_service_id', [], $service_doc_info->doc_type_for_service_id	, ['class'=>'form-control input-sm','placeholder'=>'Document type']) !!}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('order','Order: ',['class'=>'col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('order', $service_doc_info->order, ['class'=>'form-control input-sm digits','placeholder'=>'Numeric number ordering. Ex: 1,2,3']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('is_required','Required status: ',['class'=>'col-md-3 required-star']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('is_required', 1, ($service_doc_info->is_required == '1') ? true : false, ['class'=>'']) !!}
                        Mandatory
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('is_required', 0, ($service_doc_info->is_required == '0') ? true : false, ['class'=>'']) !!}
                        Optional
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('autosuggest_status','Enable Auto-suggest: ',['class'=>'col-md-3']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('autosuggest_status', 1, ($service_doc_info->autosuggest_status == '1') ? true : false, ['class'=>'']) !!}
                        Yes
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('autosuggest_status', 0, ($service_doc_info->autosuggest_status == '0') ? true : false, ['class'=>'']) !!}
                        No
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="form-group col-md-12 row">
                {!! Form::label('status','Status: ',['class'=>'text-left required-star col-md-3']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('status', 1, ($service_doc_info->status == '1') ? true : false, ['class'=>'required']) !!}
                        Active
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('status', 0, ($service_doc_info->status == '0') ? true : false, ['class'=>'required']) !!}
                        In-active
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer" style="display: block;">
    <div class="float-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="float-right">
        @if(ACL::getAccsessRight('settings','A'))
            <button type="submit" class="btn btn-primary" id="block_create_btn" name="actionBtn" value="draft">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}

<link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
<script src="{{ asset('assets/plugins/select2.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $("#process_type_id").trigger('change');

        $("#doc_id").select2();

        $("#documentCreateForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        const form = $("#documentCreateForm"); //Get Form ID
        const url = form.attr("action"); //Get Form action
        const type = form.attr("method"); //get form's data send method
        const info_err = $('.errorMsg'); //get error message div
        const info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {
            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function (msg) {
                    console.log("before send");
                    $("#block_create_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#block_create_btn").prop('disabled', true); // disable button
                },
                success: function (data) {
                    //==========validation error===========//
                    if (data.success == false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#block_create_btn").html('Submit');
                            $("#block_create_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            window.location.href = data.link;
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#block_create_btn").html('Submit');
                            $("#block_create_btn").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    const errors = data.responseJSON;
                    $("#block_create_btn").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });

    function getAttachmentType(process_type_id, attachment_id, old_data) {
        if (typeof old_data === 'undefined') {
            old_data = 0;
        }
        $.ajax({
            type: "POST",
            url: '/settings/document-v2/get-document-type',
            data: {
                process_type_id: process_type_id,
                doc_type_for_service_id: "{{\App\Libraries\Encryption::encode($service_doc_info->doc_type_for_service_id)}}",
            },
            success: function (response) {
                $("#result").html(response.result);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Unknown error occured. Please, try again after reload');
            },
            beforeSend: function (xhr) {
                //console.log('before send');
            },
            complete: function () {
                //completed
            }
        });
    }
</script>
