@php
    $addressDefaultLabel = 'Address';
    if(isset($extra)) {
        if(in_array('address2', $extra)) {
            $addressDefaultLabel = 'Address Line 1';
        }
    }
@endphp

@if($mode === 'add')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Applicant Profile
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', Auth::user()->user_first_name,
                                ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_first_name) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8  {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="iti-flag bd"></span>
                                    <span>+88</span>
                                </span>
                            </div>
                            {!! Form::text('applicant_mobile', Auth::user()->user_mobile, ['class' => 'form-control applicant-mobile',
                                 'readonly' => isset(Auth::user()->user_mobile) ?? 'readonly',
                                 'placeholder' => 'Enter Mobile Number']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email', Auth::user()->user_email, ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_email) ?? 'readonly',
                                 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone',isset($companyInfo->office_phone) ? $companyInfo->office_phone: '', ['class' => 'form-control', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', '', ['class' => 'form-control', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'applicant_address']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                @isset($extra)
                    @if(in_array('address2', $extra))
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('applicant_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 {{ $errors->has('applicant_address2') ? 'has-error' : '' }}">
                                    {!! Form::text('applicant_address2', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'applicant_address2']) !!}
                                    {!! $errors->first('applicant_address2', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>
@elseif($mode === 'edit')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Applicant Profile
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', $appInfo->applicant_name,
                                ['class' => 'form-control',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="iti-flag bd"></span>
                                    <span>+88</span>
                                </span>
                            </div>
                            {!! Form::text('applicant_mobile', $appInfo->applicant_mobile, ['class' => 'form-control applicant-mobile',
                                'readonly' => isset($appInfo->org_mobile) ?? 'readonly',
                                'placeholder' => 'Enter Mobile Number', 'id' => 'applicant_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email', $appInfo->applicant_email, ['class' => 'form-control',
                                 'readonly' => isset($appInfo->org_email) ?? 'readonly',
                                 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone', $appInfo->applicant_telephone, ['class' => 'form-control', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, $appInfo->applicant_district, ['class' => 'form-control', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 required-star']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], $appInfo->applicant_thana, ['class' => 'form-control','required' => 'required', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', $addressDefaultLabel, ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', $appInfo->applicant_address, ['class' => 'form-control', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'applicant_address']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                @isset($extra)
                    @if(in_array('address2', $extra))
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('applicant_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 {{ $errors->has('applicant_address2') ? 'has-error' : '' }}">
                                    {!! Form::text('applicant_address2', $appInfo->applicant_address2, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'applicant_address2']) !!}
                                    {!! $errors->first('applicant_address2', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset

            </div>
        </div>
    </div>
@elseif($mode === 'renew')
<div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div class="col-md-10">Applicant Profile</div>
            <div>
                <label class="amendmentEditBtn"  style="vertical-align: middle;">
                    <input type="checkbox" id="applicantProfile"  style="vertical-align: middle; "/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body"
             <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="applicantFields">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', $appInfo->applicant_name,
                                ['class' => 'form-control input_disabled',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8  {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                           <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="iti-flag bd"></span>
                                    <span>+88</span>
                                </span>
                            </div>
                            {!! Form::text('applicant_mobile', $appInfo->applicant_mobile, ['class' => 'form-control applicant-mobile input_disabled',
                                'readonly' => isset($appInfo->org_mobile) ?? 'readonly',
                                'placeholder' => 'Enter Mobile Number', 'id' => 'applicant_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                           </div>
                           </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email', $appInfo->applicant_email, ['class' => 'form-control input_disabled',
                                 'readonly' => isset($appInfo->org_email) ?? 'readonly',
                                 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone', $appInfo->applicant_telephone, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, $appInfo->applicant_district, ['class' => 'form-control input_disabled', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], $appInfo->applicant_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', $appInfo->applicant_address, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'applicant_address']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                @isset($extra)
                @if(in_array('address2', $extra))
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8 {{ $errors->has('applicant_address2') ? 'has-error' : '' }}">
                                {!! Form::text('applicant_address2', $appInfo->applicant_address2, ['class' => 'form-control  input_disabled', 'placeholder' => 'Enter  Address Line 2', 'id' => 'applicant_address2']) !!}
                                {!! $errors->first('applicant_address2', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                @endif
            @endisset
            </div>
        </div>
    </div>
@elseif($mode === 'view')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Applicant Profile
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_mobile }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('company_type', 'Telephone Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_telephone }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_email }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_district_en }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_thana_en }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8">
                            <span>: {{ $appInfo->applicant_address }}</span>
                        </div>
                    </div>
                </div>
                @isset($extra)
                    @if(in_array('address2', $extra))
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('applicant_address', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->applicant_address2 }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>
@elseif($mode === "amendment")
    <div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div>Applicant Profile</div>
            <div>
                <label class="amendmentEditBtn">
                    <input type="checkbox" id="applicantProfile"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body"
             <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="applicantFields">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', $appInfo->applicant_name,
                                ['class' => 'form-control input_disabled','readonly',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, $appInfo->applicant_district, ['class' => 'form-control input_disabled','id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], $appInfo->applicant_thana, ['class' => 'form-control input_disabled', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 ']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', $appInfo->applicant_address, ['class' => 'form-control textareaFields input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'applicant_address','rows'=>'1']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email', $appInfo->applicant_email, ['class' => 'form-control input_disabled',
                                'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8  {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="iti-flag bd"></span>
                                            <span>+88</span>
                                        </span>
                            </div>
                            {!! Form::text('applicant_mobile', $appInfo->applicant_mobile, ['class' => 'form-control input_disabled',
                            'placeholder' => 'Enter Mobile Number', 'id' => 'applicant_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4 ']) !!}
                        <div
                            class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone', $appInfo->applicant_telephone, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

<script>

    // Applicant Profile
    @if(!in_array($mode, ['add']) && (!isset($selected) || ($selected != 1)))
    @isset($appInfo->applicant_district)
    getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
        'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
    @endisset
    @endif
    getHelpText();
    // setIntlTelInput('#applicant_mobile');
    $("#applicantProfile").click(function () {
        if (this.checked) makeReadWriteByDivId('applicantFields');
        else makeReadOnlyByDivId('applicantFields');
    });

    {{--    @if(in_array($mode, ['edit']))--}}
    {{--        @isset($appInfo->applicant_district)--}}
    {{--        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},--}}
    {{--            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});--}}
    {{--        @endisset--}}
    {{--    @endif--}}
</script>
