<?php
$check_association = checkCompanyAssociation();
$bscicUsers = getBscicUser();

?>

@if (count($check_association) > 0)
    <div class="col-md-12">
        <h3>{!! trans('Dashboard::messages.company_assoc_app') !!}</h3>
    </div>

    @foreach ($check_association as $association)
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-body" style="padding: 15px 0">
                    <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="col-xs-12">
                                    <div class="col-md-12 col-12 row">
                                        <div class="col-5">
                                            <label
                                                class="form-control-label">{!! trans('Dashboard::messages.applicant_name') !!}</label>
                                        </div>
                                        <div class="col-7">
                                            : <span
                                                style="font-size: 16px">{{ $association->user_first_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12  col-12 row">
                                        <div class="col-5">
                                            <label
                                                class="form-control-label">{!! trans('Dashboard::messages.email') !!}</label>
                                        </div>
                                        <div class="col-7">
                                            : <span
                                                style="font-size: 16px">{{ $association->user_email }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 row">
                                        <div class="col-5">
                                            <label
                                                class="form-control-label">{!! trans('Dashboard::messages.mobile') !!}</label>
                                        </div>
                                        <div class="col-7 ">
                                            : <span class="input_ban"
                                                    style="font-size: 16px">{{ $association->user_mobile }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 has_border">
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <label
                                            class="form-control-label">{!! trans('Dashboard::messages.want_process') !!}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 text-center" id="userType_field">
                                        <label class="radio_label" for="consultant">
                                            {!! Form::radio('userType', 'Consultant', true, ['class' => 'identityRadio', 'id' => 'consultant']) !!}
                                            {!! trans('Dashboard::messages.consultant') !!}
                                        </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                        <label class="radio_label" for="employee">
                                            {!! Form::radio('userType', 'Employee', false, ['class' => 'identityRadio', 'id' => 'employee']) !!}
                                            {!! trans('Dashboard::messages.employee') !!}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <button type="button"
                                                value="{{ \App\Libraries\Encryption::encodeId($association->id) }}"
                                                onclick="approveAndRejectCompanyAssoc(this,'approved')"
                                                class="btn btn-primary"><i
                                                class="fa fa-check"></i> অনুমোদন
                                        </button>
                                        <button type="button"
                                                value="{{ \App\Libraries\Encryption::encodeId($association->id) }}"
                                                onclick="approveAndRejectCompanyAssoc(this,'reject')"
                                                class="btn btn-danger"><i
                                                class="fa fa-times"></i> বাতিল
                                        </button>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@if (count($check_association_from) > 0)

    @foreach ($check_association_from as $association_from)
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <h6 class="card-title"><b>{!! trans('Dashboard::messages.company_assoc_app_pending') !!}</b></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6  has_border_right">
                            <div class="col-12 row ">
                                <div class="col-5">
                                    <label
                                        class="form-control-label">{!! trans('Dashboard::messages.user_name') !!}</label>
                                </div>
                                <div class="col-7">
                                    : <span
                                        style="font-size: 16px">{{ $association_from->user_first_name }}</span>
                                </div>
                            </div>
                            <div class="col-12 row  ">
                                <div class="col-5 ">
                                    <label
                                        class="form-control-label">{!! trans('Dashboard::messages.apply_time') !!}</label>
                                </div>
                                <div class="col-7">
                                    : <span class="input_ban"
                                            style="font-size: 16px">{{ date('d-m-Y', strtotime($association_from->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="col-12 row  ">
                                <div class="col-5 ">
                                    <label class="form-control-label">{!! trans('Dashboard::messages.email') !!}</label>
                                </div>
                                <div class="col-7">
                                    : <span
                                        style="font-size: 16px">{{ $association_from->user_email }}</span>
                                </div>
                            </div>
                            <div class="col-12 row  ">
                                <div class="col-5 col-5">
                                    <label
                                        class="form-control-label">{!! trans('Dashboard::messages.mobile') !!}</label>
                                </div>
                                <div class="col-7 ">
                                    : <span class="input_ban"
                                            style="font-size: 16px">{{ $association_from->user_mobile }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="col-xs-12 row">
                                <div class="col-2 col-5">
                                    <label
                                        class="form-control-label">{!! trans('Dashboard::messages.status') !!}</label>
                                </div>
                                <div class="col-md-2 col-7">
                                    <span class="label label-warning">Pending</span>
                                </div>
                                @if(date('Y-m-d H:i',strtotime('+24 hours',strtotime($association_from->created_at))) < \Carbon\Carbon::now() )
                                    <div class="col-4 text-right">
                                        <button class="btn btn-danger btn-sm"
                                                value="{{ \App\Libraries\Encryption::encodeId($association_from->id) }}"
                                                onclick="approveAndRejectCompanyAssoc(this, 'cancel')"><i
                                                class="fa fa-remove"></i> {!!trans('Dashboard::messages.do_cancel')!!}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
