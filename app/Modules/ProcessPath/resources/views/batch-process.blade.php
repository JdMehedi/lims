<?php
$moduleName = Request::segment(1);
$proecss_type_id = Request::segment(3);
?>

<style>
    #batch-process-form .form-group {
        margin-bottom: 10px;
    }

    #delegation-notice {
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

{!! Form::open([
    'url' => 'process-path/batch-process-update',
    'method' => 'post',
    'id' => 'batch-process-form',
    'files' => true,
]) !!}

<div class="alert alert-info"
    style="border: 7px solid #32a9c2 !important; background: #bae6e1 !important; color: #000 !important;padding: 10px; overflow: visible;">


    <div class="row">
        <div class="col-sm-12">
            <div class="pull-left d-flex justify-content-between">
                <h4 class="no-margin no-padding" style="line-height: 30px">Application Process :</h4>
                <a data-toggle="modal" data-target="#remarksHistoryModal" class="float-right">
                    {!! Form::button('<i class="fa fa-eye"></i> Last Remarks', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-info',
                    ]) !!}
                </a>
            </div>
            <div class="pull-right">
                <div class="modal fade" id="remarksHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title"><i class="fa fa-th-ask"></i> Last Remarks & Attachments</h4>
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            </div>

                            <div class="modal-body">
                                <div class="list-group">
                                    <span class="list-group-item" style="color: rgba(0,0,0,0.8);">
                                        <h4 class="list-group-item-heading">Remarks</h4>
                                        @if ($process_info->status_id != 1)
                                            <p class="list-group-item-text">{{ $process_info->process_desc }}</p>
                                        @endif
                                    </span>

                                    @if (!empty($remarks_attachment))
                                        @foreach ($remarks_attachment as $remarks_attachment)
                                            <a target="_blank" href="{{ url($remarks_attachment->file) }}"
                                                style="margin-top: 10px;" class="btn btn-primary btn-xs">
                                                <i class="fa fa-save"></i> Download Attachment
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer" style="text-align:left;">
                                <button type="button" class="btn btn-danger btn-md pull-right" data-dismiss="modal">
                                    Close
                                </button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-sm-12">
            <hr style="border-top: 2px solid #32a9c2; margin-top: 2px; margin-bottom: 10px" />
        </div>
    </div>


    {{-- hidden data for data validation, update process --}}
    @if (isset($encoded_app_id))
        {!! Form::hidden('application_ids[0]', $encoded_app_id, [
            'class' => 'form-control input-sm required',
            'id' => 'application_id',
        ]) !!}
    @endif
    {!! Form::hidden('status_from', Encryption::encodeId($process_info->status_id)) !!}
    {!! Form::hidden('desk_from', Encryption::encodeId($process_info->desk_id)) !!}
    {!! Form::hidden('process_list_id', $encoded_process_list_id, ['id' => 'process_list_id']) !!}
    {!! Form::hidden('cat_id', Encryption::encodeId($cat_id), ['id' => 'cat_id']) !!}
    {!! Form::hidden(
        'data_verification',
        Encryption::encode(\App\Libraries\UtilFunction::processVerifyData($verificationData)),
        ['id' => 'data_verification'],
    ) !!}
    {!! Form::hidden('is_remarks_required', '', [
        'class' => 'form-control input-sm ',
        'id' => 'is_remarks_requir
            ed',
    ]) !!}
    {!! Form::hidden('is_file_required', '', ['class' => 'form-control input-sm ', 'id' => 'is_file_required']) !!}

    <div class="row">
        <div class="col-sm-12">
            <?php
            if(!in_array($process_info->desk_id, CommonFunction::getUserDeskIds()))
            {
            $DelegateUserInfo = CommonFunction::DelegateUserInfo($process_info->desk_id);
            ?>
            {!! Form::hidden('on_behalf_user_id', Encryption::encodeId($DelegateUserInfo->id), [
                'maxlength' => '500',
                'class' => 'form-control input-sm',
            ]) !!}
            <div class="callout callout-warning" id="delegation-notice">
                <p><i><i class="fa fa-info-circle"></i> You are working <b>On-behalf
                            of</b> {{ $DelegateUserInfo->user_full_name }}, {{ $DelegateUserInfo->designation }}
                        &nbsp;({{ $DelegateUserInfo->user_email }})</i></p>
            </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="row" style="max-height: fit-content;">
        <div class="loading" style="display: none">
            <h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2>
        </div>
        <div class="col-md-3 form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
            {!! Form::label('status_id', 'Action') !!}
            {!! Form::select('status_id', [], null, [
                'class' => 'form-control required applyStausId',
                'id' => 'application_status',
            ]) !!}
            {!! $errors->first('status_id', '<span class="help-block">:message</span>') !!}
        </div>

        <div id="visibilitysendToDeskOfficer" class="col-md-6">
            <div class="row">
                <div class="col-md-6" style="visibility: hidden; max-width: 0; height: 0">
                    <div class="col-md-12 form-group {{ $errors->has('desk_id') ? 'has-error' : '' }}">
                        {!! Form::label('desk_id', 'Send to Desk') !!}
                        {!! Form::select('desk_id', ['' => 'Select Below'], '', [
                            'class' => 'form-control dd_id required',
                            'id' => 'desk_status',
                        ]) !!}
                        {!! $errors->first('desk_id', '<span class="help-block">:message</span>') !!}
                    </div>

                </div>
                <div class="col-md-6 is_user hidden">

                    <div class="form-group  {{ $errors->has('is_user') ? 'has-error' : '' }}">
{{--                        {!! Form::label('is_user', 'Copy To') !!}<br>--}}
                        {!! Form::label('is_user', 'Designated Officer') !!}<br>
                        {{-- <span id="is_user"></span> --}}
                        {!! Form::select('is_user', ['' => 'Select user'], '', ['class' => 'form-control', 'id' => 'is_user']) !!}
                        {!! $errors->first('is_user', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 form-group {{ $errors->has('attach_file') ? 'has-error' : '' }}" style="visibility: hidden; max-width: 0; height: 0;">
            <label for="attach_file">Attach file
                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=""
                    data-original-title="To select multiple files, hold down the CTRL or SHIFT key while selecting."></i>
                <span class="text-danger" style="font-size: 9px; font-weight: bold">[File: *.pdf | Maximum 2
                    MB]</span></label>
            {!! Form::file('attach_file[]', [
                'class' => 'form-control input-md',
                'style' => 'padding: 3px 5px',
                'id' => '',
                'multiple' => true,
                'accept' => 'application/pdf',
            ]) !!}
            {!! $errors->first('attach_file', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('desk_id') ? 'has-error' : '' }}" id="pin_number">
            {!! Form::label('Enter Pin Number', '') !!}
            <input class="form-control input-md col-sm " type="text" name="pin_number">
            <span class="text-danger" style="font-size: 10px; font-weight: bold">Please check your email or phone
                number</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 form-group maxTextCountDown {{ $errors->has('remarks') ? 'has-error' : '' }}">
            <label for="remarks">Remarks <span class="text-danger" style="font-size: 9px; font-weight: bold">(Maximum
                    length 250)</span></label>
            {!! Form::textarea('remarks', !in_array($process_info->status_id, [1]) ? $process_info->process_desc : '', [
                'class' => 'form-control',
                'id' => 'remarks',
                'placeholder' => 'Enter Remarks',
                'maxlength' => 1000,
                'size' => '10x2',
            ]) !!}
            {!! $errors->first('remarks', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    {{-- AdD-on form div --}}
    <div class="row">
        <div id="FormDiv"></div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            @if ($session_get == 'batch_update')
                <i class="text-red">
                    You are processing {{ $total_process_app }} of {{ $total_selected_app }} application in batch.
                    <br>
                    Tracking no. of next application is.{{ $next_app_info }}</i>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="text-right">
                @if ($session_get == 'batch_update')
                    <input name="is_batch_update" type="hidden"
                        value="{{ \App\Libraries\Encryption::encode('batch_update') }}">
                    <input name="single_process_id_encrypt" type="hidden" value="{{ $single_process_id_encrypt }}"
                        id="process_id">

                    <a class="btn btn-info"
                        @if ($total_process_app == 1) disabled=""
                       @else href="/process/batch-process-previous/{{ $single_process_id_encrypt }}" @endif><i
                            class="fa fa-angle-double-left"></i> Previous</a>
                    <a style="padding: 6px 27px" class="btn btn-info "
                        @if ($total_process_app == $total_selected_app) disabled=""
                       @else  href="/process/batch-process-skip/{{ $single_process_id_encrypt }}" @endif>Next
                        <i class="fa fa-angle-double-right"></i></a>
                @endif
                <button class="btn btn-primary send" type="submit" value="submit">
                    Process
                </button>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
{!! Form::close() !!}

@section('footer-script2')
    <script>
        $(document).ready(function() {

            /**
             * Batch Form Validate
             * @type {jQuery}
             */
            $("#batch-process-form").validate({
                errorPlacement: function() {
                    return false;
                }
            });


            /**
             * load desk list on select 'apply status'
             * @type {jQuery}
             */
            $("#application_status").change(function() {
                var self = $(this);
                var statusId = $('#application_status').val();
                var cat_id = $('#cat_id').val();
                if (statusId !== '') {
                    $(this).after('<span class="loading_data">Loading...</span>');
                    $.ajax({
                        type: "POST",
                        url: "{{ url('process-path/get-desk-by-status') }}",
                        data: {
                            _token: $('input[name="_token"]').val(),
                            process_list_id: $('input[name="process_list_id"]').val(),
                            status_from: $('input[name="status_from"]').val(),
                            cat_id: cat_id,
                            statusId: statusId
                        },
                        success: function(response) {
                            var option = '<option value="">Select One</option>';
                            var countDesk = 0;

                            if (response.responseCode == 1) {
                                if (response.pin_number == 1) {
                                    $('#pin_number').removeClass('hidden');
                                    $('#pin_number').children('input').addClass('required');
                                    $('#pin_number').children('input').attr('disabled', false);
                                } else {
                                    $('#pin_number').addClass('hidden');
                                    $('#pin_number').children('input').removeClass('required');
                                    $('#pin_number').children('input').attr('disabled', true);

                                }

                                $('#FormDiv').html(response.html);

                                var option_selected = ((Object.keys(response.data).length ==
                                    1) ? "selected" : "");
                                $.each(response.data, function(id, value) {
                                    countDesk++;
                                    option += '<option ' + option_selected +
                                        ' value="' + id + '">' + value + '</option>';
                                });

                                // Setup required condition for Remarks field
                                if (response.remarks == 1 || statusId == 5 || statusId == 6) {
                                    $("#remarks").addClass('required');
                                    $('#is_remarks_required').val(response.remarks);
                                } else {
                                    $("#remarks").removeClass('required');
                                    $('#is_remarks_required').val('');
                                }


                                // Setup required condition for Attachment field
                                if (response.file_attachment == 1) {
                                    $("#attach_file").addClass('required');
                                    $('#is_file_required').val(response.file_attachment);
                                } else {
                                    $("#attach_file").removeClass('required');
                                    $('#is_file_required').val('');
                                }


                            }
                            $("#desk_status").html(option);

                            if (option_selected) {
                                $("#desk_status").trigger("change");
                            }

                            self.next().hide();

                            if (countDesk == 0) {
                                $('.dd_id').removeClass('required');
                                $('#sendToDeskOfficer').css('display', 'none');
                            } else {
                                $('.dd_id').addClass('required');
                                $('#sendToDeskOfficer').css('display', 'block');
                            }
                        }
                    });
                }
            });


            /**
             * load status list on page load
             * @type {jQuery}
             */
            var process_list_id = $("#process_list_id").val();
            var cat_id = $("#cat_id").val();
            $.ajaxSetup({
                async: false
            });
            var _token = $('input[name="_token"]').val();
            $.get('/process-path/ajax/load-status-list', {
                process_list_id: process_list_id,
                cat_id: cat_id
            }, function(response) {

                if (response.responseCode == 1) {

                    // Status List generate
                    var option = '';
                    option += '<option selected="selected" value="">Select Below</option>';
                    $.each(response.data, function(id, value) {
                        // select suggested desk
                        var selected = "";
                        if (response.suggested_status === parseInt(value.id)) {
                            selected = "selected";
                        }
                        option += '<option ' + selected + ' value="' + value.id + '">' + value
                            .status_name + '</option>';
                    });
                    // End Status List generate


                    // Priority List generate
                    // End Priority List generate

                    $("#application_status").html(option);
                    // $("#priority").html(PriorityOption);
                    $("#application_status").trigger("change");
                    $("#application_status").focus();
                } else if (response.responseCode == 5) {
                    alert('Without verification, application can not be processed');
                    break_for_pending_verification = 1;
                    option = '<option selected="selected" value="">Select Below</option>';
                    $("#application_status").html(option);
                    $("#application_status").trigger("change");
                    return false;
                } else {
                    $('#status_id').html('Please wait');
                }
            });
            $.ajaxSetup({
                async: true
            });
        });


        /**
         * load desk user list on select 'send to desk'
         * @type {jQuery}
         */
        $("#desk_status").change(function() {
            var self = $(this);
            var desk_id = $(this).val();
            var cat_id = $("#cat_id").val();
            var application_status = $('#application_status').val();
            if (desk_id != '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('process-path/get-user-by-desk') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        desk_to: desk_id,
                        status_from: $('input[name="status_from"]').val(),
                        desk_from: $('input[name="desk_from"]').val(),
                        statusId: application_status,
                        cat_id: cat_id,
                        process_type_id: "{{ $encoded_process_type_id }}",
                        office_id: "{{ \App\Libraries\Encryption::encodeId($process_info->office_id) }}",
                    },
                    success: function(response) {
                        var option = '<option value="">Select One</option>';
                        var countUser = 0;
                        var option_selected = ((Object.keys(response.data).length == 1) ? "selected" :
                            "");
                        $.each(response.data, function(id, value) {
                            countUser++;
                            option += '<option ' + option_selected + ' value="' + value
                                .user_id + '">' + value.user_full_name + '</option>'
                        });
                        self.next().hide();
                        if (countUser == 0) {
                            $(".is_user").addClass('hidden');
                        } else {
                            $("#is_user").html(option);
                            $(".is_user").removeClass('hidden');
                        }
                    }
                });
            }
        });


        /**
         * Check application verification and process time
         * @type {jQuery}
         */
        @if ($hasDeskOfficeWisePermission === true && ($process_type->is_special) !=1)
            function getVerificationSession() {
                var setVerificationSession = '';
                var data_verification = $("#data_verification").val();
                var process_list_id = $("#process_list_id").val();
                $.get("{{ url('process-path/check-process-validity') }}", {
                        data_verification: data_verification,
                        process_list_id: process_list_id
                    },
                    function(data, status) {
                        if (data.responseCode == 1) {
                            setVerificationSession = setTimeout(getVerificationSession, 10000);
                        } else {
                            toastr.warning('Sorry, Process data verification failed.');
                            console.log('batch process session verification fail');
                            window.location.href = "{{ url($moduleName . '/list/' . $encoded_process_type_id) }}";
                        }
                    });
            }

            setVerificationSession = setTimeout(getVerificationSession, 10000);
        @endif
    </script>
@endsection
