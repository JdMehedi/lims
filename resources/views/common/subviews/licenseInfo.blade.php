@if($mode === 'renew-serarch')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -16px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control license_no" name="license_no" value="{{isset($license_no) ? $license_no : ''}}" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" style="width: 100%;" class="btn btn-success" id="srch_license_btn">Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date', isset($appInfo->expiry_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'renew-form')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ isset($license_no) ? $license_no : ''}}" name="license_no" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="srch_license_btn">Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date', 'readonly' => isset($appInfo->license_issue_date)]) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date',isset($appInfo->expiry_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '', ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date','readonly' => isset($appInfo->expiry_date)]) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'renew-form-edit')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{isset($appInfo->license_no) ? $appInfo->license_no : '' }}" name="license_no" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1" {{ !empty($appInfo->license_no) ? 'readonly' : ''  }}  >
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="srch_license_btn" disabled>Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date',isset($appInfo->expiry_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '', ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($mode === 'view')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <span>: {{isset($appInfo->license_no) ? $appInfo->license_no : ''}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-2']) !!}
                        <div class="col-md-6  {{ $errors->has('issue_date') ? 'has-error' : '' }}">

                            <span>: {{isset($appInfo->license_issue_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : ''}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-2']) !!}
                        <div class="col-md-6 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                        <span>: {{isset($appInfo->expiry_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : ''}}</span>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@elseif($mode === 'default')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('license_no_input', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div
                            class="col-md-10 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                            {!! Form::text('license_no_input', '', ['class' => 'form-control', 'placeholder' => 'Enter License No', 'id' => 'license_no_input']) !!}
                            {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success">Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', '', ['class' => 'form-control', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date', '', ['class' => 'form-control', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'amendment')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -16px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control license_no" name="license_no" value="{{isset($license_no) ? $license_no : ''}}" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" style="width: 100%;" class="btn btn-success" id="srch_license_btn">Verify</button>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date', isset($appInfo->expiry_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($mode === 'amendment-form')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{ isset($license_no) ? $license_no : ''}}" name="license_no" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="srch_license_btn">Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date', 'readonly' => isset($appInfo->license_issue_date)]) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date',isset($appInfo->expiry_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '', ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date','readonly' => isset($appInfo->expiry_date)]) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'amendment-form-edit')
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="{{isset($appInfo->license_no) ? $appInfo->license_no : '' }}" name="license_no" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1" {{ !empty($appInfo->license_no) ? 'readonly' : ''  }}  >
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" id="srch_license_btn" disabled>Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date',isset($appInfo->expiry_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '', ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($mode === 'surrender' )
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -16px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-8">
                            <input type="text" class="form-control license_no" name="license_no" value="{{isset($license_no) ? $license_no : ''}}" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" style="width: 100%;" class="btn btn-success" id="srch_license_btn">Verify</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        <div class="col-md-4">{!! Form::label('issue_date', 'Issue Date') !!}</div>
                        <div class="col-md-8  {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        <div class="col-md-4">{!! Form::label('expiry_date', 'Expiry Date') !!}</div>
                        <div class="col-md-8 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date', isset($appInfo->expiry_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append input_disabled"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        {!! Form::label('reason_of_surrender', 'Reason of Surrender', ['class' => 'col-md-2 ']) !!}
                        <div class="col-md-10">
                            <textarea class="form-control" name="reason_of_surrender" id="reason_of_surrender"
                                      placeholder="Reason Details" rows="4" cols="4" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('surrender_date', 'Surrender Date', ['class' => 'col-md-2']) !!}
                        <div class="col-md-6 {{ $errors->has('surrender_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker11" data-target-input="nearest">
                                {!! Form::text('surrender_date', '', ['class' => 'form-control', 'id' => 'surrender_date', 'placeholder' => 'Surrender Date','required']) !!}
                                <div class="input-group-append"
                                     data-target="#datepicker11"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('surrender_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <style>
        @media (min-width: 1000px) {
            .align_input_field {
                padding-left: 0;
                margin-left: -3px;
            }
        }
    </style>
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            License Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-11">
                    <div class="form-group row">
                        {!! Form::label('license_no_input', 'License No', ['class' => 'col-md-2 ']) !!}
                        <div
                            class="col-md-10 {{ $errors->has('noc_address') ? 'has-error' : '' }}">
                            {!! Form::text('license_no_input', '', ['class' => 'form-control', 'placeholder' => 'Enter License No', 'id' => 'license_no_input']) !!}
                            {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success">Verify</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker" data-target-input="nearest">
                                {!! Form::text('issue_date', '', ['class' => 'form-control', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                <div class="input-group-append"
                                     data-target="#datepicker"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row" style="margin-top:10px;">
                        {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 align_input_field {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                            <div class="input-group date datetimepicker5"
                                 id="datepicker10" data-target-input="nearest">
                                {!! Form::text('expiry_date', '', ['class' => 'form-control', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                <div class="input-group-append"
                                     data-target="#datepicker10"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i
                                            class="fa fa-calendar"></i></div>
                                </div>
                                {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    getHelpText();
    var today = new Date();
    var yyyy = today.getFullYear();
    $('.datetimepicker5').datetimepicker({
        format: 'DD-MMM-YYYY',
        //maxDate: 'now',
        maxDate: '01/01/' + (yyyy + 20),
        minDate: '01/01/' + (yyyy - 110),
        ignoreReadonly: true,
    });
    datePickerHide('datetimepicker5');
    $("#srch_license_btn").click(function () {
        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
        btn.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "{{ url(isset($url) ? $url : 'isp-license-renew/fetchAppData') }}",
            data: {
                license_no: $('#search_license_no').val()
            },
            success: function (response) {
                if (response.responseCode == -1) {
                    alert(response.msg);
                    $('#search_license_no').val('');
                    btn.prop('disabled', false);
                    btn.html(btn_content);
                    return false;
                } else {
                    $('#search_area').hide();
                    $('#fetchedData').html(response.html);
                }
                btn.prop('disabled', false);
                btn.html(btn_content);
            },
        });
    });
</script>
