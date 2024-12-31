<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
    .cross-button {
        float:right;
        padding: 0rem .250rem !important;
    }
    .areaAddress::after {
        display: none;
    }
    .addAreaAddressRow {
        pointer-events: none;
    }
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard>.content {
        overflow: visible;
    }

    .wizard>.actions {
        width: 70% !important;
    }
    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }
    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }
    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }

    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

    .signature-upload-input {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
        background-color: blue;
    }

    .sign_div {
        position: relative;
    }

    .signature-upload {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        padding: 5px 10px;
        font-size: 1rem;
        text-align: center;
        color: #000;
        background-color: #F5FAFE;
        border-radius: 0 !important;
        border: 0;
        height: 160px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }

    .custom_error {
        outline: 1px dashed red;
    }

    .email {
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard>.actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard>.content>.body label {
            margin-top: .5em;
            margin-bottom: 0;
        }

        .tabInput {
            width: 120px;
        }

        .tabInput_sm {
            width: 75px;
        }

        .tabInputDate {
            width: 150px;
        }

        .table_responsive {
            overflow-x: auto;
        }

        /*.bootstrap-datetimepicker-widget {*/
        /*    position: relative !important;*/
        /*    top: 0 !important;*/
        /*}*/

        .prevMob {
            margin-top: 45px;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

    .wizard>.steps>ul>li {
        width: 50% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .border-header-box{
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }
    .border-header-txt{
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    /*#renewForm input[type=checkbox]{*/
    /*    vertical-align: bottom;*/
    /*    width: 15px;*/
    /*    height: 15px;*/
    /*}*/
    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="renewForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        <div class="card border-magenta" style="border-radius: 10px; ">
            <div class="card-header d:flex justify-between">
                <h4 class="float-left">Application for New BPO/ Call Center Certificate Amendment</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye" style="margin-right: 5px;"></i> Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                {!! Form::open([
                     'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                     'method' => 'post',
                     'class' => 'form-horizontal',
                     'id' => 'application_form',
                     'enctype' => 'multipart/form-data',
                     'files' => 'true',
                 ]) !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">
                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                    {!! Form::hidden('license_no', $appInfo->license_no, ['class' => 'form-control input-md required', 'id' => 'license_no']) !!}
                    {!! Form::hidden('issue_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}
                    {!! Form::hidden('renew_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->renew_tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}
                </div>
                <h3>Basic Information</h3>
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', [ 'mode' => 'amendment-form-edit'])

                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'amendment', 'selected' => 1])

                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'amendment', 'selected' => 1])

                    @includeIf('common.subviews.ContactPerson', ['mode' => 'amendment', 'selected' => 1])

                    {{-- Present Business Actives of the Applicant/Company/Group Company --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex justify-content-between areaAddress">
                            <div>Present Business Actives of the Applicant/ Company/ Group Company</div>
                            <div>
                                <label>
                                    <input type="checkbox" id="presentBusiness"/>
                                    EDIT
                                </label>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;" id="presentBusinessFields">
                        <!-- <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        {!! Form::label('present_business_actives', 'Present Business Actives', ['class' => 'col-md-4 ']) !!}
                            <div
                                class="col-md-8 {{ $errors->has('present_business_actives') ? 'has-error' : '' }}">
                                            {!! Form::select('present_business_actives', [''=>'Select',1=>'Applicant',2=>'Company',3=>'Group Company'], $appInfo->present_business_actives, ['class' => 'form-control input_disabled','readonly', 'id' => 'present_business_actives']) !!}
                        {!! $errors->first('present_business_actives', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                </div> -->
                            <div style="margin-top: 10px;">
                                {{ Form::textarea('present_business_actives', $appInfo->present_business_actives, array('class' =>'form-control textareaFields input input_disabled','id' => 'present_business_actives', 'placeholder'=>'Details', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                            </div>
                        </div>
                    </div>

                    {{-- Present Proposal (Please submite separate sheet for each centre) --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex justify-content-between areaAddress">
                            <div>Present Proposal</div>
                            <div>
                                <label>
                                    <input type="checkbox" id="areaAddressEditBtn" />
                                    EDIT
                                </label>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;"  id="amendmentProposal">

                            <div class="row">
                                <div class="col-md-6">

                                    <span class="" id="service">Service: </span>
                                    <span id="service_section">
                                        <div class="form-check form-check-inline" style="margin-left: 70px;">
                                            <label class="form-check-label" for="bpo">
                                                @isset($appInfo->proposal_service)
                                                    @if(json_decode($appInfo->proposal_service) != null && in_array('BPO',json_decode($appInfo->proposal_service)))
                                                        <input class="form-check-input input_disabled" name="proposal_service[]"
                                                               type="checkbox"
                                                               id="bpo" value="BPO" checked>
                                                    @else
                                                        <input class="form-check-input input_disabled" name="proposal_service[]"
                                                               type="checkbox"
                                                               id="bpo" value="BPO">
                                                    @endif
                                                @endisset
                                                BPO
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="callcenter">

                                                @if(json_decode($appInfo->proposal_service) != null && in_array('Call Center',json_decode($appInfo->proposal_service)))
                                                    <input class="form-check-input input_disabled" name="proposal_service[]"
                                                           type="checkbox"
                                                           id="callcenter" value="Call Center" checked>
                                                @else
                                                    <input class="form-check-input input_disabled" name="proposal_service[]"
                                                           type="checkbox"
                                                           id="callcenter" value="Call Center">
                                                @endif
                                                Call Center
                                            </label>
                                        </div>
                                    </span>

                                </div>

                                <div class="col-md-6">
                                    {!! Form::label('service_type', 'Service Type:', ['class' => 'col-md-4 ']) !!}
                                    <span id="service_type_section">
                                        <div class="form-check form-check-inline" style="margin-left: 70px;">
                                            <label class="form-check-label" for="domestic">
                                                @if(json_decode($appInfo->proposal_service_type) != null && in_array('Domestic',json_decode($appInfo->proposal_service_type)))
                                                    <input class="form-check-input" name="proposal_service_type[]"
                                                           type="checkbox" id="domestic" value="Domestic" checked>
                                                @else
                                                    <input class="form-check-input" name="proposal_service_type[]"
                                                           type="checkbox" id="domestic" value="Domestic">
                                                @endif
                                                Domestic
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="international">
                                                @if(json_decode($appInfo->proposal_service_type) != null && in_array('International',json_decode($appInfo->proposal_service_type)))
                                                    <input class="form-check-input" name="proposal_service_type[]"
                                                           type="checkbox" id="international" value="International"
                                                           checked>
                                                @else
                                                    <input class="form-check-input" name="proposal_service_type[]"
                                                           type="checkbox" id="international" value="International">
                                                @endif
                                                International
                                            </label>
                                        </div>
                                    </span>

                                </div>

                            </div>

                            <br>

                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Area Address
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">

                                    <table class="table-responsive"
                                           style="width: 100%;     display: inline-table!important;"
                                           id="areaAddressRow">
                                        <input type="hidden" id="areaAddressDataCount" name="areaAddressDataCount"
                                               value="{{ count($proposal_area) }}"/>
                                        @foreach($proposal_area as $index=>$areaData)
                                            <tr id="aa_r_{{$index+1}}">
                                                <td>
                                                    <div class="card card-magenta border border-magenta">
                                                        <div class="card-header">
                                                            Area Address Information
                                                            @if($index == 0)
                                                                <span style="float: right; cursor: pointer;" class="addAreaAddressRow" id="addAreaAddressRowId">
                                                        <i style="font-size: 20px;"
                                                           class="fa fa-plus-square" aria-hidden="true"></i>
                                                    </span>
                                                            @endif
                                                        </div>
                                                        <div class="card-body" style="padding: 15px 25px;">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('proposal_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('proposal_district') ? 'has-error' : '' }}">
                                                                            {!! Form::select("proposal_district[$index]", $districts, $areaData->proposal_district, ['class' => 'form-control aa_district proposal_district input_disabled', 'id' => 'proposal_district_'.($index+(int)1), 'onchange' => "getThanaByDistrictId('proposal_district_1', this.value, 'proposal_thana_1',0)"]) !!}
                                                                            {!! $errors->first('proposal_district', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('proposal_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('proposal_thana') ? 'has-error' : '' }}">
                                                                            {!! Form::select("proposal_thana[$index]", [], $areaData->proposal_thana, ['class' => 'form-control aa_thana proposal_thana input_disabled', 'data-id'=>$areaData->proposal_thana, 'placeholder' => 'Select district at first', 'id' => 'proposal_thana_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('proposal_thana', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('proposal_address_'.($index+(int)1), 'Address', ['class' => 'col-md-2 required-star']) !!}
                                                                        <div
                                                                            class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                                            {!! Form::text("proposal_address[$index]", $areaData->proposal_address, ['class' => 'form-control proposal_address input_disabled','readonly', 'placeholder' => 'Enter The Address', 'id' => 'proposal_address_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('proposal_address', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('proposal_no_of_seats_'.($index+(int)1), 'No.of Seats', ['class' => 'col-md-4']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('proposal_no_of_seats') ? 'has-error' : '' }}">
                                                                            {!! Form::number("proposal_no_of_seats[$index]", $areaData->proposal_no_of_seats, ['class' => 'form-control input_disabled','readonly', 'placeholder' => 'Enter the No. of Seats', 'id' => 'proposal_no_of_seats_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('proposal_no_of_seats', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('proposal_employee_'.($index+(int)1), 'Proposed of Employee', ['class' => 'col-md-4']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                                            {!! Form::number("proposal_employee[$index]", $areaData->proposal_employee, ['class' => 'form-control input_disabled','readonly', 'placeholder' => 'Enter The Proposed of Employee', 'id' => 'proposal_employee_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('proposal_employee', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('local_'.($index+(int)1), 'Local', ['class' => 'col-md-4 required-star']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('local') ? 'has-error' : '' }}">
                                                                            {!! Form::text("local[$index]", $areaData->local, ['class' => 'form-control input_disabled','readonly', 'placeholder' => 'Enter Local', 'id' => 'local_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('local', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('expatriate_'.($index+(int)1), 'Expatriate', ['class' => 'col-md-4 required-star']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                                            {!! Form::text("expatriate[$index]", $areaData->expatriate, ['class' => 'form-control input_disabled','readonly', 'placeholder' => 'Enter Expatriate', 'id' => 'expatriate_'.($index+(int)1)]) !!}
                                                                            {!! $errors->first('expatriate', '<span class="help-block">:message</span>') !!}
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
                        </div>
                    </div>

                    @includeIf('common.subviews.Shareholder', ['mode' => 'amendment', 'selected' => 1])

                    {{--Existing Call Centers (If Applicable) --}}
                    @if(count($existingCallCenterDetails))
                        @includeIf('REUSELicenseIssue::BPO.Renew.existing-call-center-details', ['mode' => 'amendment'])
                    @endif
                </fieldset>

                <h3>Attachment, Declaration & Submit</h3>
                <br>
                <fieldset>
                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Required Documents for attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

                    {{-- Declaration --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex justify-content-between areaAddress">
                            <div>Declaration</div>
                            <div>
                                <label>
                                    <input type="checkbox" id="declarations"/>
                                    EDIT
                                </label>
                            </div>
                        </div>

                        <div class="card-body" id="declarationFields" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <div class=" !font-normal">
                                                Has any Application for License/Registration of BPO/Call Center been
                                                rejected before?
                                            </div>
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No',  $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div id="if_declaration_q1_yes" style="display: none">
                                                <div class="form-group row" style="margin-top:10px;">
                                                    {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2 required-star input_disabled', 'style' => 'font-weight:400' ]) !!}
                                                    <div
                                                        class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"
                                                             id="application_date_picker"
                                                             data-target-input="nearest">
                                                            {!! Form::text('declaration_q1_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q1_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q1_application_date):'', ['class' => 'form-control input_disabled', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#application_date_picker"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">
                                                    {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null , array('class' =>'form-control input input_disabled','id' => 'declaration_q1_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class=" !font-normal">
                                                Has any License/Registration issued previously to the Applicant/any
                                                Share Holder/Partner been cancelled?
                                            </div>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null , array('class' =>'form-control input input_disabled', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                            </div>
                                        </li>
                                        <li>
                                            <div class=" !font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator
                                                Licenses from the Commission?
                                            </div>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes',  $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes' && !empty($appInfo->declaration_q3_text))? $appInfo->declaration_q3_text:'', array('class' =>'form-control input input_disabled', 'id'=>'if_declaration_q3_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>


                                            <div class="row" id="declaration_image" style= "border: none;{{($appInfo->declaration_q3 == 'Yes') ? 'display:block; margin-top: 10px;' :'display:none;'.' margin-bottom: 20px;'}}">
                                                <div class="col-md-8 d-flex" style="margin-top: 20px;">
                                                    <label style="margin-right: 20px;" id="attachment_label">Attachment</label>
                                                    <input type="file"
                                                           value="{{ $appInfo->declaration_q3_images }}"
                                                           accept="application/pdf"
                                                           class="form-control input-sm declarationFile input_disabled"
                                                           name="declaration_q3_images" id="if_declaration_q3_image"
                                                           size="300x300" onchange="generateBase64String('if_declaration_q3_image','declaration_q3_images_base64')" />
                                                    <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64"
                                                            value="{{ !empty($appInfo->declaration_q3_images)? asset($appInfo->declaration_q3_images) : '' }}">

                                                </div>
                                                @isset($appInfo->declaration_q3_images)
                                                    <a href="{{ asset($appInfo->declaration_q3_images)}}" id="declaration_q3_images_preview" target="_blank"
                                                       class="btn btn-sm btn-danger" style="margin-top: 5px">Open</a>
                                                @endisset
                                            </div>
                                        </li>

                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                            certify that <span class="i_we_dynamic">I/We</span> have carefully read the
                                            instructions/terms and conditions, for the registration and <span
                                                class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                            conditions therein. (Instructions for issuance of registration certificate
                                            for the operation of BPO/Call Center are available at www.btrc.gov.bd.)
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that this application if found incomplete in any respect and /or if found
                                            with conditional compliance shall be summarily rejected.
                                        </li>

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'accept_terms','required']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:25px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>


                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/bpo-or-call-center-new-app/list/'. Encryption::encodeId(6)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" onclick="openPreview()" name="actionBtn">Submit
                        </button>
                    @elseif($appInfo->status_id == 5)
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md"
                                value="Re-submit" onclick="openPreview()" name="actionBtn">Re-submit
                        </button>
                    @endif
                </div>
                @if(!in_array($appInfo->status_id,[5]))
                    <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                            name="actionBtn"
                            id="save_as_draft">Save as Draft
                    </button>
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>

    var selectCountry = '';
    $(document).ready(function() {

        // Registered Office Address
        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset
        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset

        // Applicant Profile
        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
        @endisset

        // Contact Information
        @isset($contact_person)
        @foreach($contact_person as $index => $person)
        @if(empty($person->district) || empty($person->upazila))
        @continue
        @endif
        getThanaByDistrictId('contact_district_{{$index + 1}}', {{ $person->district ?? ''}},
            'contact_thana_{{$index +1 }}', {{ $person->upazila ?? '' }});
        @endforeach
        @endisset

        @isset($proposal_area)
        @foreach($proposal_area as $index => $parea)
        getThanaByDistrictId('proposal_district_{{$index+1}}', {{ $parea->proposal_district ?? ''}},
            'proposal_thana_{{$index+1}}', {{ $parea->proposal_thana ?? '' }});
        @endforeach
        @endisset


        var form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    //return true;

                    var total=0;
                    $('.shareholder_share_of').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
                        alert("The value of the '% of share field' should be a total of 100.");
                        return false;
                    }
                }
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }

                if (newIndex == 2) {

                    let errorStatus = SectionValidation('#docListDiv input');
                    if(declarationSectionValidation()){
                        errorStatus = true;
                    }

                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                // return true;
                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    }else{
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }

                    if(errorStatus) return false;

                    if (currentIndex > newIndex) {
                        return true;
                    }

                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }

                if (newIndex < currentIndex) return true;

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex === 1) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");
                }else{
                    form.find('#submitForm').css('display', 'none');
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('.previous').removeClass('prevMob');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        var popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")){
                $('#accaccept_termseptTerms').removeClass('error');
                $('#accept_terms').next('label').css('color', 'black');
                $('body').css({"display": "none"});
                $("form").submit();
            } else {
                $('#acceptTerms').addClass('error');
                return false;
            }
        });

        $('.finish').on('click', function (e) {
            if(!$('#accept_terms').prop('checked')){
                $('#accept_terms').focus()
                $('#accept_terms').addClass('error');
                return false;
            }
            $('#accept_terms').removeClass('error');
            popupWindow = window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>', 'Sample', '');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;

            $("#doc_type_key").val(key);

            // loadApplicationDocs('docListDiv', key);
            loadApplicationDocs('docListDiv', null);
        }
        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function() {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: '01/01/' + (yyyy + 20),
            minDate: '01/01/1905'
        })

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{-- initail -input mobile plugin script start --}}
        $("#company_office_mobile").intlTelInput({
            hiddenInput: "company_office_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#company_factory_mobile").intlTelInput({
            hiddenInput: "company_factory_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#company_ceo_mobile").intlTelInput({
            hiddenInput: "company_ceo_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#auth_person_mobile").intlTelInput({
            hiddenInput: "auth_person_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        {{-- initail -input mobile plugin script end --}}

    })

</script>

<script>
    var selectCountry = '';

    $(document).ready(function() {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        LoadListOfDirectors();
    })
</script>
<script>
    function openModal(btn) {
        //e.preventDefault();
        var this_action = btn.getAttribute('data-action');
        if (this_action != '') {
            $.get(this_action, function(data, success) {
                if (success === 'success') {
                    $('#myModal .load_modal').html(data);
                } else {
                    $('#myModal .load_modal').html('Unknown Error!');
                }
                $('#myModal').modal('show', {
                    backdrop: 'static'
                });
            });
        }
    }

    $(document).ready(function() {
        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url('client/company-profile/load-listof-directors-session') }}",
            type: "POST",
            data: {
                {{-- app_id: "{{ Encryption::encodeId($appInfo->id) }}", --}}
                    {{-- process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}", --}}
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                var html = '';
                if (response.responseCode == 1) {

                    var edit_url = "{{ url('/client/company-profile/edit-director') }}";
                    var delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                    var count = 1;
                    $.each(response.data, function(id, value) {
                        var sl = count++;
                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' +
                            edit_url + '/' + id +
                            '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                        if (sl != 1) {
                            html += '<a data-action="' + delete_url + '/' + id +
                                '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if (response.ceoInfo != null) {
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                        if (response.ceoInfo.nationality == 'Bangladeshi') {
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if (response.ceoInfo.identity_type == 'passport') {
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nationality").val('');
                            $("#company_ceo_nid").val('');
                        } else {
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    } else {
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorList tbody').html(html);
            }
        });
    }

    //confirm delete alert
    function ConfirmDelete(btn) {
        var sure_delete = confirm("Are you sure you want to delete?");
        if (sure_delete) {
            var url = btn.getAttribute('data-action');
            $.ajax({
                url: url,
                type: "get",
                success: function(response) {
                    if (response.responseCode == 1) {
                        toastr.success(response.msg);
                    }

                    LoadListOfDirectors();
                }
            });

        } else {
            return false;
        }
    }
</script>

<script type="text/javascript">
    $(function() {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).ready(function() {
        $("#declaration_q1_yes").on('click', function() {
            $('#if_declaration_q1_yes').css('display','inline');
        });
        $("#declaration_q1_no").on('click', function() {
            $('#if_declaration_q1_yes').css('display','none');
        });

        $("#declaration_q2_yes").on('click', function() {
            $('#if_declaration_q2_yes').css('display','inline');
        });
        $("#declaration_q2_no").on('click', function() {
            $('#if_declaration_q2_yes').css('display','none');
        });

        $("#declaration_q3_yes").on('click', function() {
            $('#if_declaration_q3_yes').css('display','inline');
        });
        $("#declaration_q3_no").on('click', function() {
            $('#if_declaration_q3_yes').css('display','none');
        });
    });
</script>

<script>
    $(document).ready(function() {
        getHelpText();

        @if($appInfo->declaration_q1 == 'Yes')
        $('#if_declaration_q1_yes').css('display', 'block');
        @endif

        @if($appInfo->declaration_q2 == 'Yes')
        $('#if_declaration_q2_yes').css('display', 'block');
        @endif

        @if($appInfo->declaration_q3 == 'Yes')
        $('#if_declaration_q3_yes').css('display', 'block');
        $('#declaration_image').css('display','block');
        @endif

        @isset($appInfo->isp_license_division)
        getDistrictByDivisionId('isp_licensese_area_division',
            {{ $appInfo->isp_license_division ?? '' }},
            'isp_licensese_area_district', {{ $appInfo->isp_license_district ?? '' }});
        @endisset

        @isset($appInfo->isp_license_district)
        getThanaByDistrictId('isp_licensese_area_district', {{ $appInfo->isp_license_district ?? '' }},
            'isp_licensese_area_thana', {{ $appInfo->isp_license_upazila ?? '' }});
        @endisset


        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 1)
        $('#division').css('display','none');
        $('#district').css('display','none');
        $('#thana').css('display','none');
        @endif

        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 2)
        $('#division').css('display','inline');
        $('#district').css('display','none');
        $('#thana').css('display','none');
        @endif

        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 3)
        $('#division').css('display','inline');
        $('#district').css('display','inline');
        $('#thana').css('display','none');
        @endif

        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 4)
        $('#division').css('display','inline');
        $('#district').css('display','inline');
        $('#thana').css('display','flex');
        @endif

        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }

            if(this.value == 3){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#thana').css('display','none');
            }

            if(this.value == 4){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#thana').css('display','inline');
            }
        });

        $('#company_type').on('change', function () {
            if (this.value == "") {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I/We');
            } else if (this.value == 3) {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I');
            } else {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('We');
            }
        });

        var company_type = "{{$appInfo->company_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        $('.add_bandwidth_row').click(function(){
            let lastRowId = parseInt($('#bandwidth_tbl tr:last').attr('row_id').split('_')[1]);
            var btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-renew/add-bandwidth-row') }}",
                data: {
                    lastRowId: lastRowId,
                },
                success: function(response) {
                    $('#bandwidth_tbl tbody').append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled",false);
                }
            });
        });

        $('.addAreaAddressRow').click(function () {
            let lastRowId = parseInt($('#areaAddressRow tr:last').attr('id').split('_')[2]);
            $('#areaAddressRow').append(
                `<tr class="client-rendered-row" id="aa_r_${lastRowId + 1}">
 <td>

                            <div class="card card-magenta border border-magenta">
                                                    <div class="card-header">
                                                        Area Address Information
                                                        <span style="float: right; cursor: pointer;">
                                                             <button type="button" onclick="deleteAddressRow(aa_r_${lastRowId + 1})" class="btn btn-danger btn-sm cross-button areaAddressRow cross-button" style="float:right;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                     </span>
                                                    </div>
                                                    <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('proposal_district_${lastRowId+1}', 'District', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_district') ? 'has-error' : '' }}">
                                                    {!! Form::select('proposal_district[${lastRowId}]', $districts, '', ['class' => 'form-control proposal_district', 'id' => 'proposal_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('proposal_district_".'${lastRowId+1}'."', this.value, 'proposal_thana_".'${lastRowId+1}'."',0)"]) !!}
                {!! $errors->first('proposal_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_thana_${lastRowId+1}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select('proposal_thana[${lastRowId}]', [], '', ['class' => 'form-control proposal_thana', 'placeholder' => 'Select district at first', 'id' => 'proposal_thana_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
{!! Form::label('proposal_address_${lastRowId+1}', 'Address', ['class' => 'col-md-2']) !!}
                <div class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('proposal_address[${lastRowId}]', '', ['class' => 'form-control proposal_address', 'placeholder' => 'Enter The Address', 'id' => 'proposal_address_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_no_of_seats_${lastRowId+1}', 'No.of Seats', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_no_of_seats') ? 'has-error' : '' }}">
                                                    {!! Form::number('proposal_no_of_seats[${lastRowId}]', '', ['class' => 'form-control', 'placeholder' => 'Enter The No. of Seats', 'id' => 'proposal_no_of_seats_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_no_of_seats', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_employee_${lastRowId+1}', 'Proposed of Employee', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                    {!! Form::number('proposal_employee[${lastRowId}]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Proposed of Employee', 'id' => 'proposal_employee_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_employee', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('local_${lastRowId+1}', 'Local', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('local') ? 'has-error' : '' }}">
                    {!! Form::text('local[${lastRowId}]', '', ['class' => 'form-control local', 'placeholder' => 'Enter Local', 'id' => 'local_${lastRowId+1}']) !!}
                {!! $errors->first('local', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('expatriate_${lastRowId+1}', 'Expatriate', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                    {!! Form::text('expatriate[${lastRowId}]', '', ['class' => 'form-control expatriate', 'placeholder' => 'Enter Expatriate', 'id' => 'expatriate_${lastRowId+1}']) !!}
                {!! $errors->first('expatriate', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
            </td>
</tr>`);
            getHelpText();
            $("#areaAddressDataCount").val(lastRowId + 1);
        });

        $('.add_connectivity_row').click(function(){
            let lastRowId = parseInt($('#connectivity_tbl tr:last').attr('row_id').split('_')[1]);
            var btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-renew/add-connectivity-row') }}",
                data: {
                    lastRowId: lastRowId,
                },
                success: function(response) {
                    $('#connectivity_tbl tbody').append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled",false);
                }
            });
        });

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
{!! Form::label('contact_thana_${lastRowId+1}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
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
                                                        {!! Form::text('contact_mobile[${lastRowId}]', '', ['class' => 'form-control  contact_mobile required bd_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${lastRowId + 1}','onkeyup' => 'mobile_no_validation(this.id)']) !!}
                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
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

        $("#areaAddressEditBtn").click(function() {
            if (this.checked) {
                makeReadWriteByDivId('amendmentProposal');
                document.getElementById('addAreaAddressRowId').style.pointerEvents = 'auto';
            } else {
                makeReadOnlyByDivId('amendmentProposal');
                document.getElementById('addAreaAddressRowId').style.pointerEvents = 'none';
            }
        });

        $("#presentBusiness").click(function() {
            const element = document.getElementById('present_business_actives');
            if (this.checked) element.classList.remove('input_disabled');
            else element.classList.add('input_disabled');
        });

        $("#applicantProfile").click(function() {
            if (this.checked) makeReadWriteByDivId('applicantFields');
            else makeReadOnlyByDivId('applicantFields');
        });

        $("#companyInfo").click(function() {
            if (this.checked) makeReadWriteByDivId('companyFields');
            else makeReadOnlyByDivId('companyFields');
        });

        $("#declarations").click(function() {
            const elements = document.querySelectorAll('#declarationFields input, #declarationFields label');
            if (this.checked) {
                elements.forEach(item => {
                    item.classList.remove('input_disabled');
                })
            } else {
                elements.forEach(item => {
                    item.classList.add('input_disabled');
                })
            };
        });

        $("#contactPerson").click(function() {
            if (this.checked) {
                makeReadWriteByDivId('contactFields');
                document.getElementById('contactAddBtn').style.pointerEvents = 'auto';
            } else {
                makeReadOnlyByDivId('contactFields');
                document.getElementById('contactAddBtn').style.pointerEvents = 'none';
            }
        });

        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // $("#type_of_isp_licensese").change(function() {
{{--        @if(!empty($appInfo->isp_license_type))--}}
{{--        --}}{{--var total_investment = $('#total_investment').val();--}}
{{--        --}}{{--var vat_percentage = parseFloat('{{ $vat_percentage }}');--}}

{{--        let oss_fee = 0;--}}
{{--        let vat = 0;--}}

{{--        $.ajax({--}}
{{--            type: "POST",--}}
{{--            url: "{{ url('isp-license-renew/get-payment-data-by-license-type') }}",--}}
{{--            data: {--}}
{{--                process_type_id: {{ $process_type_id }},--}}
{{--                payment_type: 1,--}}
{{--                license_type: {{$appInfo->isp_license_type}}--}}
{{--            },--}}
{{--            success: function(response) {--}}
{{--                if(response.responseCode == -1){--}}
{{--                    alert(response.msg);--}}
{{--                    return false;--}}
{{--                }--}}

{{--                oss_fee = parseFloat(response.data.oss_fee);--}}
{{--                vat = parseInt(response.data.vat);--}}
{{--            },--}}
{{--            complete: function() {--}}
{{--                --}}{{--console.log({{ $process_type_id }});--}}
{{--                var unfixed_amounts = {--}}
{{--                    1: 0,--}}
{{--                    2: oss_fee,--}}
{{--                    3: 0,--}}
{{--                    4: 0,--}}
{{--                    5: vat,--}}
{{--                    6: 0--}}
{{--                };--}}
{{--                loadPaymentPanel('', '{{ $process_type_id }}', '1',--}}
{{--                    'payment_panel',--}}
{{--                    "{{ CommonFunction::getUserFullName() }}",--}}
{{--                    "{{ Auth::user()->user_email }}",--}}
{{--                    "{{ Auth::user()->user_mobile }}",--}}
{{--                    "{{ Auth::user()->contact_address }}",--}}
{{--                    unfixed_amounts);--}}
{{--            }--}}
{{--        });--}}
{{--        @endif--}}
        // });
    });


    function isCheckedAcceptTerms() {
        let errorStatus = false;
        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            errorStatus = true;
        } else {
            $('#accept_terms').removeClass('error');
            errorStatus = false;
        }
        return errorStatus;
    }

    function ReSubmitForm() {
        let errorStatus = isCheckedAcceptTerms();
        if (SectionValidation('#docListDiv input')) {
            errorStatus = true;
        }
        if (declarationSectionValidation()) {
            errorStatus = true;
        }
        if (errorStatus) return false;
        $("#application_form").submit();
    }

    function generateBase64String(source, destination){
        const inputfile = document.getElementById(source);
        const file = inputfile.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            document.getElementById(destination).value = reader.result;
        };
        reader.onerror = function (error) {
            console.log(error);
        };
    }

    function openPreview() {

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').focus()
            $('#accept_terms').addClass('error');
            return false;
        }
        $('#accept_terms').removeClass('error');
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function declarationSectionValidation(){
        let error_status = false ;
        if($("#declaration_q1_yes").is(":checked") && $("#if_declaration_q1_yes").val() === ""){
            $("#if_declaration_q1_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === ""){
            $("#if_declaration_q2_yes").addClass('error');
            error_status = true;
        }

        let declaration_q3_images_preview = $("#declaration_q3_images_preview").attr('href');
        if($("#declaration_q3_yes").is(":checked") && ( $("#if_declaration_q3_yes").val() === "" &&  !Boolean(declaration_q3_images_preview) )){
            console.log($("#if_declaration_q3_yes").val());
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }

        return error_status;
    }

    function SectionValidation(selector) {
        const requiredClientFields = document.querySelectorAll(selector);
        let errorStatus = false;
        for (const elem of requiredClientFields) {
            if (elem.classList.contains('required') && !elem.value) {
                elem.classList.add('error');
                errorStatus = true;
            }
        }
        return errorStatus;
    }

    function deleteAddressRow(element) {
        element.remove();
    }
</script>

