@extends('layouts.admin')

@section('page_heading',trans('messages.rollback'))

@section('style')
    <style>
        html, body {
            overflow: hidden;
        }
    </style>
@endsection

@section('content')

    @include('partials.messages')
    <div class="row">
    <div class="col-lg-12">
        {!! Form::open(array('url' => '/settings/app-rollback/update', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'appRollback',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-right">

                    <div class="btn-group" role="group" aria-label="">
                        <a type="button" class="btn btn-info" target="_blank" href="{{ url($openAppRoute) }}">
                            Open Application
                        </a>
{{--                        <a type="button" class="btn btn-info" target="_blank" href="{{ url($BiRoute) }}">--}}
{{--                            View Corresponding Basic Information--}}
{{--                        </a>--}}
                    </div>
                </div>
                <h5>
                    Application Rollback
                </h5>
            </div>

            {!! Form::hidden('process_list_id', Encryption::encodeId($appInfo->id)) !!}

            <div class="card-body">
                <section>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page"><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                            <li class="breadcrumb-item active" aria-current="page"><strong>Current Status : </strong>
                                @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                @else {!! $appInfo->status_name !!}
                                @endif
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> <strong>Current Desk :</strong>
                                @if($appInfo->desk_id != 0)
                                    {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                @else
                                    Applicant
                                @endif</li>
                            <li class="breadcrumb-item active" aria-current="page"><strong>Current Desk User :</strong>
                                @if($appInfo->user_id != 0)
                                    {!! $appInfo->desk_user_name !!}
                                @else
                                    N/A
                                @endif</li>
                        </ol>
                    </section>

                <div class="border border-info">
                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="row">
                                <label class="col-md-4"><strong>Company:</strong></label>
                                <div class="col-md-6">
                                    {{$appInfo->company_name}}
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                <label class="col-md-4"><strong>Submission Date:</strong></label>
                                <div class="col-md-6">
                                    {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                                </div>
                            </div>
                            </div>
                        </div>

                </div>


                @if(Auth::user()->user_type == '1x101')
                    <div class="row pt-3">
                        <div class="col-md-4">

                                {!! Form::label('status_id', 'Apply Status', ['class' =>'col-sm-12 required-star']) !!}
                                <div class="col-sm-12">
                                    {!! Form::select('status_id', $status, '', ['class' => 'form-control required applyStausId', 'id' => 'application_status']) !!}
                                    {!! $errors->first('status_id','<span class="help-block">:message</span>') !!}
                                </div>

                        </div>
{{--                        <div id="sendToDeskOfficer">--}}
                            <div class="col-md-3">
                                {!! Form::label('desk_id','Send to Desk', ['class' =>'col-sm-12 required-star']) !!}
                                <div class="col-sm-12">
                                    {!! Form::select('desk_id', $desk, '', ['class' => 'form-control dd_id required', 'id' => 'desk_status']) !!}
                                    {!! $errors->first('desk_id','<span class="help-block">:message</span>') !!}
                                </div>

                            </div>

                            <div class="col-md-4 is_user">
                                {!! Form::label('is_user','Send to User', ['class' =>'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::select('is_user', [], '', ['class' => 'form-control', 'id' => 'is_user', 'placeholder'=>'Select One']) !!}
                                    {!! $errors->first('is_user','<span class="help-block">:message</span>') !!}
                                </div>

                            </div>
{{--                        </div>--}}
                    </div>
                @endif

                <div class="row pt-5">
                    <div class="col-md-12">
                        <div class="maxTextCountDown {{$errors->has('remarks') ? 'has-error' : ''}}">
                            <div class="row">
                                <label for="remarks" class="col-md-12 required-star">Remarks <span class="text-danger" style="font-size: 9px; font-weight: bold">(Maximum length 5000)</span></label>
                                <div class=" col-md-12">
                                    {!! Form::textarea('remarks', '',['class'=>'form-control required','id'=>'remarks', 'placeholder'=>'Enter Remarks','maxlength' => 5000, 'size' => "10x3"]) !!}
                                    {!! $errors->first('remarks','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="clearfix"></div>--}}
            </div>

            <div class="card-footer">
                <div class="float-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($appInfo->updated_at, $appInfo->updated_by) !!}
                </div>
                <div class="float-right">
                    <a href="{{ url('settings/app-rollback') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>

                        @if(Auth::user()->user_type == '4x404')
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-chevron-circle-right"></i>
                                Rollback to my desk
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-chevron-circle-right"></i>
                                Update
                            </button>
                        @endif

                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
    </div>
@endsection

@section('footer-script')
    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" type="text/javascript"></script>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(document).ready(function () {

            $("#appRollback").validate({
                errorPlacement: function () {
                    return false;
                },
            });

            $("#desk_status").change(function () {
                var self = $(this);
                var desk_id = $(this).val();

                if (desk_id != '') {
                    $(this).after('<span class="loading_data">Loading...</span>');
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ url('settings/get-user-by-desk') }}",
                        data: {
                            _token: $('input[name="_token"]').val(),
                            desk_to: desk_id
                        },
                        success: function (response) {
                            console.log(response);
                            var option = '<option value="">Select One</option>';
                            var countUser = 0;
                            var option_selected = ((Object.keys(response.data).length == 1) ? "selected" : "");
                            $.each(response.data, function (id, value) {
                                countUser++;
                                option += '<option ' + option_selected + ' value="' + value.user_id + '">' + value.user_full_name + '</option>'
                            });

                            self.next().hide();
                            if (countUser == 0) {
                                $('#is_user').removeClass('required');
                                $(".is_user").addClass('hidden');
                            } else {
                                $("#is_user").html(option);
                                // $('#is_user').addClass('required');
                                $(".is_user").removeClass('hidden');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
