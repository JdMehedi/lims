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
            Name Of Authorized Signatory And Contact Person
        </div>
        <div class="card-wrapper"
             id="contactPersonRow" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_1">
                <div class="card-header">
                    Contact Person
                    <span style="float: right; cursor: pointer;" class="addContactPersonRow m-l-auto">
                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                            </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="card-body" style="padding: 0px 0px;" id="contact_1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_person_name_1', 'Name', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_1']) !!}
                                        {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_designation_1', 'Designation', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_designation[1]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_designation_1']) !!}
                                        {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_mobile_1', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8  {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                        <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="iti-flag bd"></span>
                                                <span>+88</span>
                                            </span>
                                        </div>
                                        {!! Form::text('contact_mobile[1]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_1']) !!}
                                        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_person_email_1', 'Email', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_1']) !!}
                                        {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_website_1', 'Website', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_website[1]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Please start with https://', 'id' => 'contact_website_1']) !!}
                                        {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_district_1', 'District', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                        {!! Form::select('contact_district[1]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_1', 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                        {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_thana_1', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                        {!! Form::select('contact_thana[1]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_1']) !!}
                                        {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_person_address_1', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'contact_person_address_1']) !!}
                                        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @isset($extra)
                                @if(in_array('address2', $extra))
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_person_address_2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                            <div
                                                class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_address2[1]', '', ['class' => 'form-control contact_person_address_2', 'placeholder' => 'Enter  Address Line 2', 'id' => 'contact_person_address_2']) !!}
                                                {!! $errors->first('contact_person_address2', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endisset
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom:45px!important;">
                                    {!! Form::label('correspondent_contact_photo0_1', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                        {{--start--}}
                                        <div class="row"
                                             style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                            <div class="col-md-8">
                                                <input type="file"
                                                       style="border: none; margin-bottom: 5px;"
                                                       class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                       name="correspondent_contact_photo[1]"
                                                       id="correspondent_contact_photo_1"
                                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_1', 'correspondent_contact_photo_base64_1')"
                                                       size="300x300"/>
                                                <span class="text-success"
                                                      style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank"
                                                                                                   href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="center-block image-upload"
                                                       for="correspondent_photo0_1">
                                                    <figure>
                                                        <img
                                                            style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                            src="{{asset('assets/images/demo-user.jpg') }}"
                                                            class="img-responsive img-thumbnail"
                                                            id="correspondent_contact_photo_preview_1"/>
                                                    </figure>
                                                    <input type="hidden" id="correspondent_contact_photo_base64_1"
                                                           name="correspondent_contact_photo_base64[1]"/>
                                                </label>
                                            </div>
                                        </div>
                                        {{--end--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'edit')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Contact Person
        </div>
        <div class="card-wrapper" id="contactPersonRow" style="padding: 15px 25px;">
            {{--                            @foreach($isp_contact_person as $index => $person)--}}
            {{--                                <div class="card-body" id="contact_{{$index}}" style="padding: 5px 0 0;<?php if($index > 0) echo 'border-top: 1px solid #999999;'?>">--}}
            {{--                                    @if($index > 0)--}}
            {{--                                        <div class="row">--}}
            {{--                                            <div class="col-md-12">--}}
            {{--                                                <button type="button" onclick="deleteContactRow(contact_{{$index}})" class="btn btn-danger btn-sm shareholderRow" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
            {{--                                            </div>--}}
            {{--                                        </div>--}}
            {{--                                    @endif--}}
            @foreach($contact_person as $index => $person)
                <div class="card card-magenta border border-magenta" style="margin-top: 20px;"
                     id="contact_{{ $index }}">
                    <div class="card-header">
                        Contact Person Information
                        @if( $index == 0)
                            <span style="float: right; cursor: pointer;" class="addContactPersonRow m-l-auto">
                                                 <i style="font-size: 20px;" class="fa fa-plus-square"
                                                    aria-hidden="true"></i>
                                            </span>
                        @else
                            <span style="float: right; cursor: pointer;">
                                                <button type="button" onclick="deleteContactRow(contact_{{ $index }})"
                                                        class="btn btn-danger btn-sm shareholderRow cross-button m-l-auto"><strong><i
                                                            style="font-size: 16px;" class="fa fa-times"
                                                            aria-hidden="true"></i></strong></button>
                                            </span>
                        @endif
                    </div>
                    <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                        {{--                                        <div class="card-body" style="padding: 0px 0px;">--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_name_'.$index, 'Name', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                        {!! Form::text("contact_person_name[$index]", $person->name, ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_name_'.$index]) !!}
                                        {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_designation_'.$index, 'Designation', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                        {!! Form::text("contact_designation[$index]", $person->designation, ['class' => 'form-control contact_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_designation_'.$index]) !!}
                                        {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label("contact_mobile_".$index, 'Mobile Number', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8  {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                        <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="iti-flag bd"></span>
                                                <span>+88</span>
                                            </span>
                                        </div>
                                        {!! Form::text("contact_mobile[$index]", $person->mobile, ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => "contact_mobile_".$index , 'onkeyup' => 'mobile_no_validation(this.id)' ]) !!}
                                        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_email_'.$index, 'Email', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                        {!! Form::text("contact_person_email[$index]", $person->email, ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_email_'.$index]) !!}
                                        {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_website_'.$index, 'Website', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                        {!! Form::text("contact_website[$index]", $person->website, ['class' => 'form-control contact_website', 'placeholder' => 'Please start with https://', 'id' => 'contact_website_'.$index]) !!}
                                        {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label("contact_district_".$index, 'District', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                        {!! Form::select("contact_district[$index]", $districts, $person->district, ['class' => 'form-control contact_district', 'id' => "contact_district_".$index, 'onchange' => "getThanaByDistrictId(\"contact_district_$index\", this.value,\"contact_thana_$index\",0)"]) !!}
                                        {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label("contact_thana_".$index, 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                        {!! Form::select("contact_thana[$index]", [], $person->upazila, ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => "contact_thana_".$index]) !!}
                                        {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_person_address_'.$index, $addressDefaultLabel, ['class' => 'col-md-4']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                        {!! Form::text("contact_person_address[$index]", $person->address, ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'contact_person_address_'.$index]) !!}
                                        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @isset($extra)
                                @if(in_array('address2', $extra))
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_person_address_2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                            <div
                                                class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                {!! Form::text("contact_person_address2[$index]", $person->address2, ['class' => 'form-control contact_person_address_2', 'placeholder' => 'Enter  Address Line 2', 'id' => 'contact_person_address_'.$index]) !!}
                                                {!! $errors->first('contact_person_address2', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endisset
                            <div class="col-md-6">
                                <div class="form-group row" style="margin-bottom:45px!important;">
                                    {!! Form::label("correspondent_contact_photo0_{{$index}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                        <div class="row"
                                             style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                            <div class="col-md-8">
                                                <input type="file"
                                                       style="border: none; margin-bottom: 5px;"
                                                       value="{{ $person->image }}"
                                                       class="form-control correspondent_photo input-sm shareholderImg {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                       name="correspondent_contact_photo[{{$index}}]"
                                                       id="correspondent_contact_photo_{{$index}}"
                                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_{{$index}}', 'correspondent_contact_photo_base64_{{$index}}')"
                                                       size="300x300"/>
                                                <span class="text-success"
                                                      style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank"
                                                                                                   href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="center-block image-upload"
                                                       for="correspondent_photo0_1">
                                                    <figure>
                                                        <img
                                                            style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                            src="{{ !empty($person->image) ? $person->image : url('assets/images/demo-user.jpg') }}"
                                                            class="img-responsive img-thumbnail"
                                                            id="correspondent_contact_photo_preview_{{$index}}"/>
                                                    </figure>
                                                    <input type="hidden"
                                                           id="correspondent_contact_photo_base64_{{$index}}"
                                                           value="{{ $person->image }}"
                                                           name="correspondent_contact_photo_base64[{{$index}}]"/>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{--                        </div>--}}
    </div>
@elseif($mode === 'renew')
<div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div class="col-md-10">Name Of Authorized Signatory And Contact Person</div>
            <div>
                <label class="amendmentEditBtn" style="vertical-align: middle;">
                    <input type="checkbox" id="contactPerson" style="vertical-align: middle;"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="contactFields">

        <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                   id="contactPersonRow">
                <input type="hidden" id="contactPersonDataCount" name="contactPersonDataCount"
                       value="{{ count($contact_person) }}"/>
                @foreach($contact_person as $key=>$contactData)
                    <tr id="cp_r_{{$key+1}}">
                        <td>
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Contact Person
                                    @if($key == 0)
                                        <span style="float: right; cursor: pointer; pointer-events: none"
                                              class="addContactPersonRow" id="contactAddBtn">
                                            <i style="font-size: 20px;" class="fa fa-plus-square"
                                               aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </div>
                                <div
                                    class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_name_'.($key+(int)1), 'Contact Person', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_name[$key]", $contactData->name, ['class' => 'form-control contact_name input_disabled','placeholder' => 'Enter Name', 'id' => 'contact_name_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_district_'.($key+(int)1), 'District', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                    {!! Form::select("contact_district[$key]", $districts, $contactData->district, ['class' => 'form-control contact_district input_disabled', 'id' => 'contact_district_'.($key+(int)1), 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                                    {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_thana_'.($key+(int)1), 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 input-group {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select("contact_thana[$key]", isset($thana) ? $thana : [], $contactData->upazila, ['class' => 'form-control contact_thana input_disabled', 'data-id'=>$contactData->cntct_prsn_upazila, 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_website_'.($key+(int)1), 'Website', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_website[$key]", $contactData->website, ['class' => 'form-control contact_website input_disabled', 'placeholder' => 'Enter Website', 'id' => 'contact_website_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_mobile_'.($key+(int)1), 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <span class="iti-flag bd"></span>
                                                                <span>+88</span>
                                                            </span>
                                                        </div>
                                                    {!! Form::text("contact_mobile[$key]", $contactData->mobile, ['class' => 'form-control contact_mobile input_disabled', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <div class="form-group row">
                                                            {!! Form::label('contact_fax_'.($key+(int)1), 'Fax', ['class' => 'col-md-4']) !!}
                                            <div
                                                class="col-md-8 {{ $errors->has('contact_fax') ? 'has-error' : '' }}">
                                                                {!! Form::text("contact_fax[$key]", $contactData->cntct_prsn_fax, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter Fax', 'id' => 'contact_fax_'.($key+(int)1)]) !!}
                                            {!! $errors->first('contact_fax', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div> -->

                                            <div class="form-group row">
                                                {!! Form::label('contact_designation_'.($key+(int)1), 'Designation', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_designation[$key]", $contactData->designation, ['class' => 'form-control shareholder_designation input_disabled', 'placeholder' => 'Enter The Name of Designation', 'id' => 'contact_designation_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_email_'.($key+(int)1), 'Email Address', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_email[$key]", $contactData->email, ['class' => 'form-control contact_email input_disabled', 'placeholder' => 'Enter Email', 'id' => 'contact_email_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_person_address_'.($key+(int)1), 'Address', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_address[$key]", $contactData->address, ['class' => 'form-control contact_person_address input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        @isset($extra)
                                        @if(in_array('address2', $extra))
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_person_address_2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text("contact_person_address2[$key]", $contactData->address2, ['class' => 'form-control contact_person_address_2 input_disabled', 'placeholder' => 'Enter  Address Line 2', 'id' => 'contact_person_address_'.($key)]) !!}
                                                        {!! $errors->first('contact_person_address2', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endisset
                                        <div class="col-md-6">
                                            <div class="form-group row" style="margin-bottom:45px!important;">
                                                {!! Form::label("correspondent_contact_photo0_{{$key}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value="{{ $contactData->image }}"
                                                                   class="form-control correspondent_photo input-sm shareholderImg {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                   name="correspondent_contact_photo[{{$key}}]"
                                                                   id="correspondent_contact_photo_{{$key}}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_{{$key}}', 'correspondent_contact_photo_base64_{{$key}}')"
                                                                   size="300x300"/>
                                                            <span class="text-success"
                                                                  style="font-size: 9px; font-weight: bold; display: block;">
                                                                [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                <p style="font-size: 12px;"><a target="_blank"
                                                                                               href="https://picresize.com/">You may update your image.</a></p>
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="center-block image-upload"
                                                                   for="correspondent_photo0_1">
                                                                <figure>
                                                                    <img
                                                                        style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                        src="{{ !empty($contactData->image) ?$contactData->image : url('assets/images/demo-user.jpg') }}"
                                                                        class="img-responsive img-thumbnail"
                                                                        id="correspondent_contact_photo_preview_{{$key}}"/>
                                                                </figure>
                                                                <input type="hidden"
                                                                       id="correspondent_contact_photo_base64_{{$key}}"
                                                                       value="{{ $contactData->image }}"
                                                                       name="correspondent_contact_photo_base64[{{$key}}]"/>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@elseif($mode === 'view')
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Contact Person
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            @foreach($contact_person as $key => $item)
                <div class="card card-magenta border border-magenta" style="margin-top: 20px;">
                    <div class="card-header">
                        Contact Person Information
                    </div>
                    <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->designation }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->mobile }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_website', 'Website', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->website }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_district', 'District', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->contact_district_name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->contact_upazila_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>: {{ $item->address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @isset($extra)
                                @if(in_array('address2', $extra))
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $item->address2 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endisset
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_image', 'Image:', ['class' => 'col-md-4 ']) !!}
                                    <div
                                        class="col-md-8 {{ $errors->has('contact_image') ? 'has-error' : '' }}">
                                        <label class="center-block image-upload"
                                               for="correspondent_photo0_1">
                                            <figure>
                                                <img
                                                    style="height: 99px; width: 95px; border: 1px solid #EBEBEB;"
                                                    src="{{$item->image !=""? asset($item->image):asset('assets/images/demo-user.jpg') }}"
                                                    class="img-responsive img-thumbnail"/>
                                            </figure>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@elseif($mode === 'amendment')
    <div class="card card-magenta border border-magenta">
        <div class="card-header d-flex justify-content-between areaAddress">
            <div>Name Of Authorized Signatory And Contact Person</div>
            <div>
                <label class="amendmentEditBtn">
                    <input type="checkbox" id="contactPerson"/>
                    EDIT
                </label>
            </div>
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?> id="contactFields">

            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                   id="contactPersonRow">
                <input type="hidden" id="contactPersonDataCount" name="contactPersonDataCount"
                       value="{{ count($contact_person) }}"/>
                @foreach($contact_person as $key=>$contactData)
                    <tr id="cp_r_{{$key+1}}">
                        <td>
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Contact Person
                                    @if($key == 0)
                                        <span style="float: right; cursor: pointer; pointer-events: none"
                                              class="addContactPersonRow" id="contactAddBtn">
                                            <i style="font-size: 20px;" class="fa fa-plus-square"
                                               aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </div>
                                <div
                                    class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_name_'.($key+(int)1), 'Contact Person', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_name[$key]", $contactData->name, ['class' => 'form-control contact_name input_disabled','placeholder' => 'Enter Name', 'id' => 'contact_name_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_district_'.($key+(int)1), 'District', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                    {!! Form::select("contact_district[$key]", $districts, $contactData->district, ['class' => 'form-control contact_district input_disabled', 'id' => 'contact_district_'.($key+(int)1), 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                                    {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_thana_'.($key+(int)1), 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 input-group {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select("contact_thana[$key]", [], $contactData->upazila, ['class' => 'form-control contact_thana input_disabled', 'data-id'=>$contactData->cntct_prsn_upazila, 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_person_address_'.($key+(int)1), 'Address', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_address[$key]", $contactData->address, ['class' => 'form-control contact_person_address input_disabled', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_mobile_'.($key+(int)1), 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <span class="iti-flag bd"></span>
                                                                <span>+88</span>
                                                            </span>
                                                        </div>
                                                    {!! Form::text("contact_mobile[$key]", $contactData->mobile, ['class' => 'form-control contact_mobile input_disabled', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <div class="form-group row">
                                                            {!! Form::label('contact_fax_'.($key+(int)1), 'Fax', ['class' => 'col-md-4']) !!}
                                            <div
                                                class="col-md-8 {{ $errors->has('contact_fax') ? 'has-error' : '' }}">
                                                                {!! Form::text("contact_fax[$key]", $contactData->cntct_prsn_fax, ['class' => 'form-control input_disabled', 'placeholder' => 'Enter Fax', 'id' => 'contact_fax_'.($key+(int)1)]) !!}
                                            {!! $errors->first('contact_fax', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div> -->

                                            <div class="form-group row">
                                                {!! Form::label('contact_designation_'.($key+(int)1), 'Designation', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_designation[$key]", $contactData->designation, ['class' => 'form-control shareholder_designation input_disabled', 'placeholder' => 'Enter The Name of Designation', 'id' => 'contact_designation_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_email_'.($key+(int)1), 'Email Address', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_person_email[$key]", $contactData->email, ['class' => 'form-control contact_email input_disabled', 'placeholder' => 'Enter Email', 'id' => 'contact_email_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('contact_website_'.($key+(int)1), 'Website', ['class' => 'col-md-4']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                    {!! Form::text("contact_website[$key]", $contactData->website, ['class' => 'form-control contact_website input_disabled', 'placeholder' => 'Enter Website', 'id' => 'contact_website_'.($key+(int)1)]) !!}
                                                    {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row" style="margin-bottom:45px!important;">
                                                {!! Form::label("correspondent_contact_photo0_{{$key}}", 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value="{{ $contactData->image }}"
                                                                   class="form-control correspondent_photo input-sm shareholderImg {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                   name="correspondent_contact_photo[{{$key}}]"
                                                                   id="correspondent_contact_photo_{{$key}}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_{{$key}}', 'correspondent_contact_photo_base64_{{$key}}')"
                                                                   size="300x300"/>
                                                            <span class="text-success"
                                                                  style="font-size: 9px; font-weight: bold; display: block;">
                                                                [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                <p style="font-size: 12px;"><a target="_blank"
                                                                                               href="https://picresize.com/">You may update your image.</a></p>
                                                            </span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="center-block image-upload"
                                                                   for="correspondent_photo0_1">
                                                                <figure>
                                                                    <img
                                                                        style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                        src="{{ !empty($contactData->image) ? $contactData->image : url('assets/images/demo-user.jpg') }}"
                                                                        class="img-responsive img-thumbnail"
                                                                        id="correspondent_contact_photo_preview_{{$key}}"/>
                                                                </figure>
                                                                <input type="hidden"
                                                                       id="correspondent_contact_photo_base64_{{$key}}"
                                                                       value="{{ $contactData->image }}"
                                                                       name="correspondent_contact_photo_base64[{{$key}}]"/>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif

<script>
    getHelpText();
    // setIntlTelInput('.contact_mobile ');
    @if(!in_array($mode, ['add']) && (!isset($selected) || ($selected != 1)))
    // Contact Information
    @isset($contact_person)
    @foreach($contact_person as $index => $person)
    @if(empty($person->district) || empty($person->upazila))
    @continue
    @endif
    getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},
        'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});
    @endforeach
    @endisset
    @endif

    @if(in_array($mode, ['amendment']) && (!isset($selected) || ($selected != 1)))
    // Contact Information
    @isset($contact_person)
    @foreach($contact_person as $index => $person)
    @if(empty($person->district) || empty($person->upazila))
    @continue
    @endif
    getThanaByDistrictId('contact_district_{{$index+1}}', {{ $person->district ?? ''}},
        'contact_thana_{{$index+1}}', {{ $person->upazila ?? '' }});
    @endforeach
    @endisset
    @endif

    @if(in_array($mode, ['add', 'edit']))
    $(".addContactPersonRow").on('click', function () {
        let lastRowId = $('#contactPersonRow .card:last').attr('id').split('_')[1];
        let updateRowId = parseInt(lastRowId) + 1;
        // console.log(lastRowId);
        // return false;
        $("#contactPersonRow").append(`
                    <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_${updateRowId}">
                            <div class="card-header">
                                Contact Person
                                <span style="float: right; cursor: pointer;">
                                     <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                </span>
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                <div class="card-body" style="padding: 5px 0px 0px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('contact_person_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                        {!! Form::text('contact_person_name[${updateRowId}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_${updateRowId}']) !!}
        {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
<div class="col-md-6">
<div class="form-group row">
{!! Form::label('contact_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation',  'placeholder' => 'Enter Designation', 'id' => 'contact_designation_${updateRowId}']) !!}
        {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}
        <div class="col-md-8  {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
              <div class="input-group">
              <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="iti-flag bd"></span>
                                                <span>+88</span>
                                            </span>
                                        </div>
                {!! Form::text('contact_mobile[${updateRowId}]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${updateRowId}']) !!}
        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
        </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_person_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_${updateRowId}']) !!}
        {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
</div>


<div class="row">
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_website_${updateRowId}', 'Website', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                {!! Form::text('contact_website[${updateRowId}]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Please start with https://', 'id' => 'contact_website_${updateRowId}']) !!}
        {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_district_${updateRowId}', 'District', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                            {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_'.'${updateRowId}', 'onchange' => 'getThanaByDistrictId("contact_district_${updateRowId}",this.value, "contact_thana_${updateRowId}",0)']) !!}
        {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_thana_${updateRowId}', 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.'${updateRowId}']) !!}
        {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group row">
{!! Form::label('contact_person_address_${updateRowId}', 'Address Line 1', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter Address Line 1', 'id' => 'contact_person_address_${updateRowId}']) !!}
        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
    </div>
</div>
<div class=row>
@isset($extra)
        @if(in_array('address2', $extra))
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_address_2_${updateRowId}', 'Address Line 2', ['class' => 'col-md-4']) !!}
        <div class="col-md-8 {{ $errors->has('contact_person_address2') ? 'has-error' : '' }}">
                {!! Form::text('contact_person_address2[${updateRowId}]', '', ['class' => 'form-control contact_person_address_2', 'placeholder' => 'Enter Address Line 2', 'id' => 'contact_person_address_2_${updateRowId}']) !!}
        {!! $errors->first('contact_person_address2', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
@endif
        @endisset

        <div class="col-md-6">
            <div class="form-group row" style="margin-bottom:45px!important;">
{!! Form::label('correspondent_contact_photo0_${lastRowId + 1}', 'Image', ['class' => 'col-md-4 required-star']) !!}
        <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                    <div class="col-md-8">
                        <input type="file"
                               style="border: none; margin-bottom: 5px;"
                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                               name="correspondent_contact_photo[${updateRowId}]" id="correspondent_contact_photo_${updateRowId}"
                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_${updateRowId}', 'correspondent_contact_photo_base64_${updateRowId}')"
                                                                               size="300x300" />
                                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="center-block image-upload"
                                                                               for="correspondent_photo0_${updateRowId}">
                                                                            <figure>
                                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                                     class="img-responsive img-thumbnail"
                                                                                     id="correspondent_contact_photo_preview_${updateRowId}" />
                                                                            </figure>
                                                                            <input type="hidden" id="correspondent_contact_photo_base64_${updateRowId}"
                                                                                   name="correspondent_contact_photo_base64[${updateRowId}]" />
                                                                        </label>
                                                                    </div>
                                                                </div>

                </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    `);
        getHelpText();
        // setIntlTelInput('.contact_mobile ');
    });

    function deleteContactRow(element) {
        element.remove();
    }
    @endif

    @if($mode === "amendment" || $mode === "renew")
    //contact person row
    $(".addContactPersonRow").on('click', function () {

        let lastRowId = parseInt($('#contactPersonRow tr:last').attr('id').split('_')[2]);
        $('#contactPersonRow').append(
            `<tr class="client-rendered-row" id="cp_r_${lastRowId + 1}">
                    <td><div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Contact Person
                                                <span style="float: right; cursor: pointer;">
                                                     <button type="button" class="btn btn-danger btn-sm contactPersonRow cross-button" style="float:right;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_name_${lastRowId+1}', 'Name', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_name[${lastRowId}]', '', ['class' => 'form-control  contact_name required', 'placeholder' => 'Enter Name', 'id' => 'contact_name_${lastRowId+1}']) !!}
            {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_district_${lastRowId+1}', 'District', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_district[${lastRowId}]', $districts, '', ['class' => 'form-control  contact_district required', 'id' => 'contact_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('contact_district_".'${lastRowId+1}'."', this.value, 'contact_thana_".'${lastRowId+1}'."',0)"]) !!}
            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_thana_${lastRowId+1}', 'Upazila/ Thana', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_thana[${lastRowId}]', [], '', ['class' => 'form-control  contact_thana required', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_${lastRowId+1}']) !!}
            {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_person_address_${lastRowId+1}', 'Address', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[${lastRowId}]', '', ['class' => 'form-control  contact_person_address required', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${lastRowId+1}']) !!}
            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_mobile_${lastRowId + 1}', 'Mobile Number', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
<div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="iti-flag bd"></span>
                                                <span>+88</span>
                                            </span>
                                        </div>{!! Form::text('contact_mobile[${lastRowId}]', '', ['class' => 'form-control  contact_mobile required bd_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${lastRowId + 1}','onkeyup' => 'mobile_no_validation(this.id)']) !!}
            {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_designation_${lastRowId + 1}', 'Designation', ['class' => 'col-md-4 required-star']) !!}
            <div
                class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                    {!! Form::text('contact_designation[${lastRowId + 1}]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter The Designation', 'id' => 'contact_designation_${lastRowId + 1}']) !!}
            {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_email_${lastRowId + 1}', 'Email Address', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_email[${lastRowId}]', '', ['class' => 'form-control contact_email', 'placeholder' => 'Enter Email', 'id' => 'contact_email_${lastRowId + 1}']) !!}
            {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_website_${lastRowId + 1}', 'Website', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_website[]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Enter Website', 'id' => 'contact_website_${lastRowId + 1}']) !!}
            {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
<div class=row>
        <div class="col-md-6">
            <div class="form-group row" style="margin-bottom:45px!important;">
{!! Form::label('correspondent_contact_photo0_${lastRowId + 1}', 'Image', ['class' => 'col-md-4 required-star']) !!}
            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                    <div class="col-md-8">
                        <input type="file"
                               style="border: none; margin-bottom: 5px;"
                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                               name="correspondent_contact_photo[${lastRowId}]" id="correspondent_contact_photo_${lastRowId + 1}"
                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_contact_photo_preview_${lastRowId + 1}', 'correspondent_contact_photo_base64_${lastRowId + 1}')"
                                                                               size="300x300" />
                                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="center-block image-upload"
                                                                               for="correspondent_photo0_${lastRowId + 1}">
                                                                            <figure>
                                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                                     class="img-responsive img-thumbnail"
                                                                                     id="correspondent_contact_photo_preview_${lastRowId + 1}" />
                                                                            </figure>
                                                                            <input type="hidden" id="correspondent_contact_photo_base64_${lastRowId + 1}"
                                                                                   name="correspondent_contact_photo_base64[${lastRowId}]" />
                                                                        </label>
                                                                    </div>
                                                                </div>

                </div>
        </div>
    </div>
    </div>
</div>
</div>
</td>
</tr>`);
        getHelpText();
        // setIntlTelInput('.contact_mobile');
        $("#contactPersonDataCount").val(lastRowId + 1);
    });

    $('#contactPersonRow').on('click', '.contactPersonRow', function () {
        let prevDataCount = $("#contactPersonDataCount").val();

        var child = $(this).closest('tr').nextAll();

        child.each(function () {
            var id = $(this).attr('id');
            var idx = $(this).children('.row-index').children('p');
            var dig = parseInt(id.substring(1));
            idx.html(`Row ${dig - 1}`);
            $(this).attr('id', `R${dig - 1}`);
        });
        $(this).closest('tr').remove();
        rowId--;
        $("#contactPersonDataCount").val(prevDataCount - 1);
    });
    $("#contactPerson").click(function () {
        if (this.checked) {
            makeReadWriteByDivId('contactFields');
            document.getElementById('contactAddBtn').style.pointerEvents = 'auto';
        } else {
            makeReadOnlyByDivId('contactFields');
            document.getElementById('contactAddBtn').style.pointerEvents = 'none';
        }
    });

    @endif


</script>
