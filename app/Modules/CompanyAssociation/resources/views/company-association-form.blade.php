<?php

$divisions = getDivision();
$regType = getCompanyRegistrationType();
$companyType = getCompanyType();
?>
<link rel="stylesheet" href="{{ asset("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type="text/css">
    /* Latest compiled and minified CSS included as External Resource*/

    /* Optional theme */

    /*@import url('//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css');*/

    .addressField {
        width: 79.5%;
        float: right;
    }
    .input_disabled {
        background-color: #E9ECEF !important;
        pointer-events: none !important;
    }
    .stepwizard-step p {
        margin-top: 0px;
        color:#666;
    }
    .stepwizard-row {
        display: table-row;
    }

    .type-based-show{
        display: none;
    }

    .show-after-select{
        display: inline;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        /*opacity: 1 !important;
        filter: alpha(opacity=100) !important;*/
    }
    .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
        opacity:1 !important;
        color:#bbb;
    }
    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content:" ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-index: 0;
    }

    .stepwizard-step {
        font-weight: bold;
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <strong>Company Association</strong>
            </div>
            <div class="card-body">
                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-xs-6">
                            <a href="#step-1" type="button" class="btn btn-success btn-circle step1">1</a>
                            <p><small>Step 1</small></p>
                        </div>
                        <div class="stepwizard-step col-xs-6 ">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle step2" disabled="disabled">2</a>
                            <p><small>Step 2</small></p>
                        </div>
                    </div>
                </div>

                <form role="form" method="post" action="/client/company-association/store">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
                    <div class="setup-content" id="step-1">
                        {{--		            <div class="panel-heading">--}}
                        {{--		                 <h3 class="panel-title">প্রতিষ্ঠানের তথ্য</h3>--}}
                        {{--		            </div>--}}
                        {{--		            <div class="panel-body">--}}
                        <div class="form-group">
                            <div class="row">
{{--                                <div class="col-md-6 form-input row">--}}
{{--                                    {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),--}}
{{--                                    ['class'=>'col-md-5 required-star']) !!}--}}
{{--                                    <div class="col-md-7 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">--}}
{{--                                        {!! Form::text('company_name_bangla', '', ['placeholder' => trans("CompanyProfile::messages.write_company_name_bangla"),--}}
{{--                                       'class' => 'form-control input-md required bnEng','id'=>'company_name_bangla','required'=>'required']) !!}--}}
{{--                                        {!! $errors->first('company_name_bangla','<span class="help-block">:message</span>') !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-6 form-input row">
                                    {!! Form::label('company_name_english', trans('Company Name'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('company_name_english') ? 'has-error': ''}}">
                                        {!! Form::text('company_name_english',Session::has('associated_company_name')?Session::get('associated_company_name'):'', ['placeholder' => trans("CompanyProfile::messages.write_company_name_english"),
                                       'class' => 'form-control input-md required bnEng input_disabled','id'=>'company_name_english','required'=>'required']) !!}
                                        {!! $errors->first('company_name_english','<span class="help-block">:message</span>') !!}
                                        <span id="name_validation" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 form-input row">
                                    {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('company_type') ? 'has-error': ''}}">
                                        {!! Form::select('company_type_id', $companyType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_type_id','required'=>'required']) !!}
                                        {!! $errors->first('company_type_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">

{{--                                <div class="col-md-6 form-input row">--}}
{{--                                    {!! Form::label('reg_type_id', trans('CompanyProfile::messages.reg_type'),--}}
{{--                                    ['class'=>'col-md-5 required-star']) !!}--}}
{{--                                    <div class="col-md-7 {{$errors->has('reg_type_id') ? 'has-error': ''}}">--}}
{{--                                        {!! Form::select('reg_type_id', $regType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'reg_type_id','required'=>'required']) !!}--}}
{{--                                        {!! $errors->first('reg_type_id','<span class="help-block">:message</span>') !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-input row">
                                    {!! Form::label('company_office_division_id', trans('CompanyProfile::messages.division'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('company_office_division_id') ? 'has-error': ''}}">
                                        {!! Form::select('company_office_division_id', $divisions, '', ['class' =>'form-control input-md required','required'=>'required','placeholder'=> trans("CompanyProfile::messages.select_division"),
'id'=>'company_office_division_id', 'onchange'=>"getDistrictByDivisionId('company_office_division_id', this.value, 'company_office_district_id')"]) !!}
                                        {!! $errors->first('company_office_division_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 form-input row">
                                    {!! Form::label('company_office_district_id', trans('CompanyProfile::messages.district'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('company_office_district_id') ? 'has-error': ''}}">
                                        {!! Form::select('company_office_district_id', [], '', ['class' =>'form-control input-md required','required'=>'required','placeholder'=> trans("CompanyProfile::messages.select_district"),
'id'=>'company_office_district_id', 'onchange'=>"getThanaByDistrictId('company_office_district_id', this.value, 'company_office_thana_id')"]) !!}
                                        {!! $errors->first('company_office_district_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-input row">
                                    {!! Form::label('company_office_thana_id', trans('CompanyProfile::messages.thana'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('company_office_thana_id') ? 'has-error': ''}}">
                                        {!! Form::select('company_office_thana_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_office_thana_id','required'=>'required']) !!}
                                        {!! $errors->first('company_office_thana_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 form-input row type-based-show">
                                    {!! Form::label('incorporation_number', trans('Incorporation Number'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('incorporation_number') ? 'has-error': ''}}">
                                        {!! Form::number('incorporation_number','', ['placeholder' => 'Enter Incorporation Number','class' => 'form-control input-md','id'=>'incorporation_number']) !!}
                                        {!! $errors->first('incorporation_number','<span class="help-block">:message</span>') !!}
                                        <span id="incorporation_number_error" class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-input row d-none" id="incorporationDateSection" >
                                    {!! Form::label('incorporation_date', trans('Incorporation Date'),
                                    ['class'=>'col-md-5 required-star']) !!}
                                    <div class="col-md-7 {{$errors->has('incorporation_date') ? 'has-error': ''}}">
                                        <div class="input-group date datetimepicker4" id="datepicker34" data-target-input="nearest">
                                            {!! Form::text('incorporation_date','', ['placeholder' => 'Enter Incorporation Date',
                                       'class' => 'form-control input-md','id'=>'incorporation_date']) !!}
                                            <div class="input-group-append"
                                                 data-target="#datepicker34"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i
                                                        class="fa fa-calendar"></i></div>
                                            </div>
                                        {!! $errors->first('incorporation_date','<span class="help-block">:message</span>') !!}
                                            <span id="incorporation_date_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="warning_message" class="alert alert-dismissible" role="alert"></div>

                        <button style="margin-right: 30px" class="btn btn-primary nextBtn float-right" type="button">Search & Next</button>
                    </div>
                    {{--		        </div>--}}



                    <div class="panel panel-primary setup-content" id="step-2">
                        {{--		            <div class="panel-heading">--}}
                        {{--		                 <h3 class="panel-title">ব্যবহার কারীর তথ্য</h3>--}}
                        {{--		            </div>--}}
                        <div class="panel-body">
                            <div id="status_message" class="alert alert-dismissible" role="alert"></div>

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
{{--                                            <div class="col-md-6 row">--}}
{{--                                                {!! Form::label('company_name_bangla_data', trans('CompanyProfile::messages.company_name_bangla'),--}}
{{--                                                ['class'=>'col-md-5']) !!}--}}
{{--                                                <div class="col-md-7">--}}
{{--                                                    : <span id="company_name_bangla_data"></span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <div class="col-md-6 form-input row">
                                                {!! Form::label('company_name_data', trans('CompanyProfile::messages.company_name_english'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="company_name_english_data"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 row">
                                                {!! Form::label('company_type', trans('CompanyProfile::messages.company_type'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="company_type"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 row">
                                                {!! Form::label('division', trans('CompanyProfile::messages.division'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="division"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-input row">
                                                {!! Form::label('district', trans('CompanyProfile::messages.district'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="district"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 row">
                                                {!! Form::label('thana', trans('CompanyProfile::messages.thana'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="thana"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 row privateCompanySection d-none" >
                                                {!! Form::label('incorporation_number_view','Incorporation Number',
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="incorporation_number_view"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group privateCompanySection d-none">
                                        <div class="row">
                                            <div class="col-md-6 row " >
                                                {!! Form::label('incorporation_date_view', 'Incorporation Date',
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="incorporation_date_view"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 form-input user_name hidden row">
                                                {!! Form::label('user_name', trans('CompanyProfile::messages.user_name'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="user_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-input email hidden row">
                                                {!! Form::label('email', trans('CompanyProfile::messages.email'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span id="email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 form-input phone hidden row">
                                                {!! Form::label('email', trans('CompanyProfile::messages.mobile_no'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span class="input_ban" id="mobile"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-input last_update hidden row">
                                                {!! Form::label('last_update', trans('CompanyProfile::messages.last_login'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    : <span class="" id="last_update"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- TODO:: Need to discuss  --}}
{{--                            <div class="form-group col-md-12 row" id="user_type_section" style="display:none;">--}}
{{--                                {!! Form::label('user_type', 'Type of user :', ['class' => 'col-md-3 required-star']) !!}--}}
{{--                                <div class="col-md-9">--}}
{{--                                    <label--}}
{{--                                        class="radio-inline">{!! Form::radio('user_type','company','', ['class'=>'user_type required','id'=>'user_type_company']) !!}--}}
{{--                                        প্রতিষ্ঠনের ব্যবহারকারী</label>--}}
{{--                                    <label--}}
{{--                                        class="radio-inline">{!! Form::radio('user_type', 'bscic','', ['class'=>'user_type required','id'=>'user_type_bscic']) !!}--}}
{{--                                        কর্তৃপক্ষ ব্যবহারকারী</label>--}}
{{--                                    {!! $errors->first('user_type','<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-md-12 form-input row" id="company_user_section"--}}
{{--                                 style="display: none;">--}}
{{--                                {!! Form::label('company_user','ব্যবহারকারী (প্রতিষ্ঠান )',--}}
{{--                                ['class'=>'col-md-3 required-star']) !!}--}}
{{--                                <div class="col-md-4 {{$errors->has('company_user') ? 'has-error': ''}}">--}}
{{--                                    {!! Form::select('company_user', [], '', ['class' =>'form-control input-md required','placeholder'=>'নির্বাচন করুন','id'=>'company_user']) !!}--}}
{{--                                    {!! $errors->first('company_user','<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-md-12 form-input row" id="bscic_user_section"--}}
{{--                                 style="display: none;">--}}
{{--                                {!! Form::label('bscic_user','ব্যবহারকারী (বিসিক)',--}}
{{--                                ['class'=>'col-md-3 required-star']) !!}--}}
{{--                                <div class="col-md-4 {{$errors->has('bscic_user') ? 'has-error': ''}}">--}}
{{--                                    {!! Form::select('bscic_user',$bscicUsers, '', ['class' =>'form-control input-md required','placeholder'=> 'নির্বাচন করুন','id'=>'bscic_user']) !!}--}}
{{--                                    {!! $errors->first('bscic_user','<span class="help-block">:message</span>') !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group" id="save_company" style="display:none;">--}}
{{--                                <div class="checkbox form-input">--}}
{{--                                    <label style="font-weight: 600;">--}}
{{--                                        {!! Form::checkbox('save_company_yes',1,null, array('id'=>'save_company_yes', 'class'=>'', 'checked' => false)) !!}--}}
{{--                                        Do you want to incorporate new companies?--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                           <input type="hidden" id="org_company_id" name="org_company_id" value="{{ Encryption::encodeId(Auth::user()->working_company_id) }}" >
                            <button class="btn btn-primary float-right" type="submit">Submit</button>
                            <button class="btn btn-success float-right previousBtn" style="margin-right:10px;"
                                    type="button">Previous
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
<script src="{{ asset("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>

<script type="text/javascript">

    var today = new Date();
    var yyyy = today.getFullYear();
    $('.datetimepicker4').datetimepicker({
        format: 'DD-MMM-YYYY',
        maxDate: 'now',
        minDate: '01/01/' + (yyyy - 110),
        ignoreReadonly: true,
    });

    $(document).ready(function () {
        $('.nextBtn ').attr('disabled', true);

        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn'),
            allPreviousBtn = $('.previousBtn')
        ;

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-success').addClass('btn-default');
                $item.addClass('btn-success');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allPreviousBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                previousStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

            previousStepWizard.trigger('click');
            $('#step-2').hide();
            $('.step2').removeClass('btn-success');
            $('#step-1').show();
            $('.step1').addClass('btn-success');
        });

        allNextBtn.click(async function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='checkbox'],input[type='radio'],input[type='url'],select"),
                isValid = true;



            $(".form-input").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-input").addClass("has-error");
                }
            }

            if(isValid && curStepBtn == 'step-1'){

                const companyType = $('#company_type_id option:selected').text() || '';
                const companyTypeId = $('#company_type_id option:selected').val() || '';
                const companyOfficeDivision = $('#company_office_division_id option:selected').text() || '';
                const companyOfficeDistrict = $('#company_office_district_id option:selected').text() || '';
                const companyOfficeThana =  $('#company_office_thana_id option:selected').text() || '';

                $.ajax({
                    url: "{{ url("client/company-association/company-info")}}",
                    type: "POST",
                    data: {
                        companyname: $('#company_name_english').val(),
                        companyThana: $('#company_office_thana_id').val(),
                        companyType: $('#company_type_id').val(),
                        companyIncorporationNumber: $('#incorporation_number').val(),
                        companyIncorporationDate: $('#incorporation_date').val(),
                        companyId: $('#org_company_id').val() || 0,
                        _token : $('input[name="_token"]').val()
                    },
                    success: function(response){
                        if (response.responseCode == 1){

                            $('#step-1').hide();
                            $('.step1').removeClass('btn-success');
                            $('#step-2').show();
                            $('.step2').addClass('btn-success');
                            // $("#save_company_yes").prop('checked', false)
                            if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');

                            var message = '';
                            var cl = ""
                            $("#status_message").removeClass('alert alert-danger alert-success');
                            if(response.status == 1){

                                // cl = "alert alert-success"
                                // message = "আপনার কোম্পানিটি পাওয়া গিয়েছে। এপ্রুভাল এর জন্য নিচের ব্যবহারকারীকে এসাইন করুন";

                                $("#company_name_bangla_data").html(response.company_data.org_nm_bn);
                                $("#company_name_english_data").html(response.company_data.org_nm);
                                $("#company_type").html(response.company_data.company_type_bn || companyType );

                                $("#reg_type").html(response.company_data.regist_type_bn);
                                $("#division").html(response.company_data.division || companyOfficeDivision);
                                $("#district").html(response.company_data.district || companyOfficeDistrict);
                                $("#thana").html(response.company_data.thana || companyOfficeThana );
                                $("#user_name").html(response.last_login_data.user_name);
                                $('#last_update').html(response.last_login_data.time);

                                // Hide mobile number charecter
                                var number = response.last_login_data.user_mobile;
                                var numberLength = number.length;
                                if (numberLength % 2 == 0){
                                    var middleIndex = numberLength/2;
                                }else{
                                    var middleIndex = (numberLength-1)/2;
                                }
                                var startIndexOfMiddle = middleIndex-1;
                                var endIndexOfMiddle = middleIndex+2;
                                var hidden_number = number.substring(0, startIndexOfMiddle) + "***" + number.substring(endIndexOfMiddle);
                                $("#mobile").html(hidden_number);

                                // Hide email charecter
                                var email = response.last_login_data.user_email;
                                var parts = email.split("@"), len = parts[0].length;
                                if (len % 2 == 0){
                                    var middleIndex = len/2;
                                }else{
                                    var middleIndex = (len-1)/2;
                                }
                                var startIndexOfMiddle = middleIndex-1;
                                var endIndexOfMiddle = middleIndex+2;
                                var hidden_email = email.substring(0, startIndexOfMiddle) + "***" + email.substring(endIndexOfMiddle);
                                $("#email").html(hidden_email);

                                $(".last_update").removeClass('hidden');
                                $(".email").removeClass('hidden');
                                $(".phone").removeClass('hidden');
                                $(".user_name").removeClass('hidden');

                                // User Type Section
                                $("#user_type_section").show();
                                $("#bscic_user_section").hide()
                                $("#company_user_section").hide();
                                $("#save_company").hide();

                                $("#org_company_id").val(response.company_id);
                                // $("#save_company_yes").prop('required', false);
                                $(".user_type").prop('required',true);
                                html = '<option value="">নির্বাচন করুন</option>';

                                $.each(response.companyusers, function(index, value) {
                                    html += '<option value="' + index+ '" >' + value + '</option>';
                                });
                                $('#company_user').html(html);
                            } else{
                                $("#company_name_bangla_data").html($("#company_name_bangla").val());
                                $("#company_name_english_data").html($("#company_name_english").val());
                                $("#company_type").html(companyType);
                                $("#reg_type").html($("#reg_type_id option:selected").text());
                                $("#division").html(companyOfficeDivision);
                                $("#district").html(companyOfficeDistrict);
                                $("#thana").html(companyOfficeThana);
                                $(".last_update").addClass('hidden');
                                $(".email").addClass('hidden');
                                $(".phone").addClass('hidden');
                                $(".user_name").addClass('hidden');
                                // cl = "alert alert-danger"
                                // message = "Company Not Found";
                                $("#user_type_section").hide();
                                $("#bscic_user_section").hide()
                                $("#company_user_section").hide();
                                $("#save_company").show();
                                // $("#save_company_yes").prop('required', true);
                                // $(".user_type").prop('required', false);
                            }

                            // Incorporation Section show for private company
                            if(companyTypeId == 3){
                                $(".privateCompanySection").removeClass('d-none');
                                $("#incorporation_number_view").html($("#incorporation_number").val());
                                $("#incorporation_date_view").html($("#incorporation_date").val());
                            }else{
                                $(".privateCompanySection").addClass('d-none');
                            }

                            // $("#status_message").text(message);
                            // $("#status_message").addClass(cl);

                        }else if(response.responseCode == 2) {
                            // swal({
                            //     type: 'warning',
                            //     text: 'কোম্পানিটি ইতমধ্যে আসোসিয়েটেড অবস্থায় আছে',
                            // });

                            toastr.error("কোম্পানিটি ইতমধ্যে আসোসিয়েটেড অবস্থায় আছে")
                        }else if(response.responseCode == 3) {
                            // swal({
                            //     type: 'warning',
                            //     text: 'ইতিমধ্যেই এই কোম্পানিটি এসোসিয়েশন এর জন্য আবেদন করা হয়েছে',
                            // });
                            toastr.error("ইতিমধ্যেই এই কোম্পানিটি এসোসিয়েশন এর জন্য আবেদন করা হয়েছে")
                        }
                    },
                    error: function (jqXHR, exception) {
                        alert("something was wrong")
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                });

                // $.when(
                //    getCompanyData(),
                //    console.log(2345)
                //  ).then(function() {
                //    console.log('22')
                //    if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
                //  });

            }else{
                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            }


        });

        $('div.setup-panel div a.btn-success').trigger('click');
    });


    // function getCompanyData() {
    //
    //
    //
    // }

    $(".user_type").on('change',function(){
        var userType = $(this).val();
        if(userType == "bscic"){
            $("#bscic_user_section").show();
            $("#company_user_section").hide();
            $("#bscic_user").prop('required',true);
            $("#company_user").prop('required',false);
        }else if(userType == "company"){
            $("#company_user_section").show();
            $("#bscic_user_section").hide()
            $("#bscic_user").prop('required',false);
            $("#company_user").prop('required',true);
        }else{
            $("#bscic_user_section").hide()
            $("#company_user_section").hide();
            $("#bscic_user").prop('required',false);
            $("#company_user").prop('required',false);
        }
    })


    //show input field based on company type
    $('#company_type_id').on('change', function(){
        let typeArray = [3, 4];
        let selectedCompanyTypeId = parseInt(document.querySelector('#company_type_id').value);

        let incorporationNumber = document.querySelector('#incorporation_number');
        let incorporationDate = document.querySelector('#incorporation_date');

        if (typeArray.includes(selectedCompanyTypeId)) {
            incorporationNumber.parentNode.parentNode.classList.remove('type-based-show');
            document.getElementById('incorporationDateSection').classList.remove('d-none')

            incorporationNumber.setAttribute('required', true);
            incorporationDate.setAttribute('required', true);
        }else{
            incorporationNumber.parentNode.parentNode.classList.add('type-based-show');
            document.getElementById('incorporationDateSection').classList.add('d-none')

            incorporationNumber.removeAttribute('required');
            incorporationDate.removeAttribute('required');
        }

    });

    $('#company_name_english').on('keyup',function(){
        var name = $('#company_name_english').val();
        var lastword = name.split(" ").pop();
        var companyTypeId = $('#company_type_id').val();

        if (companyTypeId == 3 || companyTypeId == 4){
            if (lastword == 'Ltd' || lastword == 'ltd' || lastword == 'Ltd.' || lastword == 'ltd.' || lastword == 'limited' || lastword == 'Limited' || lastword == 'limited.' || lastword == 'Limited.'){
                $('.nextBtn ').attr('disabled', false);
                $('#name_validation').text('');
            }else{
                $('.nextBtn ').attr('disabled', true);
                $('#name_validation').text('If the company name does not have limited/ltd at the end, Public/Private Limited cannot be selected.');
            }
        }

        if (companyTypeId == 1 || companyTypeId == 2 || companyTypeId == 5){
            if (lastword == 'Ltd' || lastword == 'ltd' || lastword == 'Ltd.' || lastword == 'ltd.' || lastword == 'limited' || lastword == 'Limited' || lastword == 'limited.' || lastword == 'Limited.'){
                $('#name_validation').text('If the company name does not have limited/ltd at the end, Public/Private Limited cannot be selected.');
                $('.nextBtn ').attr('disabled', true);
            }else{
                $('#name_validation').text('');
                $('.nextBtn ').attr('disabled', false);
            }

        }

    })

    $('#company_type_id').on('change',function(){
        var companyTypeId = $('#company_type_id').val();
        var name = $('#company_name_english').val();
        var lastword = name.split(" ").pop();

        if (companyTypeId == 3 || companyTypeId == 4){
            $('#name_validation').text('If the company name does not have limited/ltd at the end, Public/Private Limited cannot be selected.');

            if ((lastword == 'Ltd' || lastword == 'ltd' || lastword == 'Ltd.' || lastword == 'ltd.' || lastword == 'limited' || lastword == 'Limited' || lastword == 'limited.' || lastword == 'Limited.') && name != ''){
                $('.nextBtn ').attr('disabled', false);
                $('#name_validation').text('');
            }else{
                $('#name_validation').text('If the company name does not have limited/ltd at the end, Public/Private Limited cannot be selected.');
                $('.nextBtn ').attr('disabled', true);
            }

        }

        if (companyTypeId == 1 || companyTypeId == 2 || companyTypeId == 5){
            if (lastword == 'Ltd' || lastword == 'ltd' || lastword == 'Ltd.' || lastword == 'ltd.' || lastword == 'limited.' || lastword == 'Limited.' || lastword == 'limited' || lastword == 'Limited' || name == ''){
                $('#name_validation').text('If the company name does not have limited/ltd at the end, Public/Private Limited cannot be selected.');
                $('.nextBtn ').attr('disabled', true);
            }else{
                $('#name_validation').text('');
                $('.nextBtn ').attr('disabled', false);
            }

        }

        if (companyTypeId ==  6 || companyTypeId == 7){
            $('.nextBtn ').attr('disabled', false);
            $('#name_validation').text('');
        }
    })

    // if (ltd_validation === false){
    //     $('.nextBtn ').attr('disabled', true);
    // }else{
    //     $('.nextBtn ').attr('disabled', false);
    // }
</script>
