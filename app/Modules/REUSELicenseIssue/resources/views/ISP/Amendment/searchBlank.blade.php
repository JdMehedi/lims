<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />

<style>
    .font-normal {
        font-weight: normal;
    }
    .input-disabled {
        pointer-events: none;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .w-60 {
        width: 70px !important;
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

    /*.wizard > .actions {*/
    /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }
    .verticalAlignMiddle{
        vertical-align: middle !important;
    }

    /*form label {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*.table>thead:first-child>tr:first-child>th {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*td {*/
    /*    font-size: 14px;*/
    /*}*/

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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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
        width: 33.2% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

    .wizard>.actions{
        pointer-events: none;
        opacity: 0.6;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="AmendmentForm">
        <div class="card border-magenta">
            <h4 class="card-header">Application For Internet Service Provider (ISP) License Amendment</h4>
            <div class="card-body">
                {!! Form::open([
                                    'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                                    'method' => 'post',
                                    'class' => 'form-horizontal',
                                    'id' => 'application_form',
                                    'enctype' => 'multipart/form-data',
                                    'files' => 'true'
                                ])
                            !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @include('common.subviews.licenseInfo', ['mode' => 'amendment', 'url' => 'isp-license-ammendment/fetchAppData'])
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Types of ISP License Applied for --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Types Of ISP License Applied for
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('type_of_isp_licensese', 'Types Of ISP License', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('type_of_isp_licensese') ? 'has-error' : '' }}">
                                            {!! Form::select('type_of_isp_licensese', [''=>'Select',1=>'Nationwide',2=>'Divisional',3=>'District', 4=>'Thana/Upazila'], '', ['class' => 'form-control', 'id' => 'type_of_isp_licensese']) !!}
                                            {!! $errors->first('type_of_isp_licensese', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="division" style="display: none;">
                                    <div class="form-group row">
                                        {!! Form::label('isp_licensese_area_division', 'Division', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('isp_licensese_area_division') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_division', $division, '', ['class' => 'form-control isp_licensese_area_division', 'id' => 'isp_licensese_area_division', 'data-type' => '2', 'onchange' => "getDistrictByDivisionId('isp_licensese_area_division', this.value, 'isp_licensese_area_district',0)"]) !!}
                                            {!! $errors->first('isp_licensese_area_division', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="district" style="display: none;"></div>
                                <div class="col-md-6" id="thana" style="display: none;">
                                    <div class="form-group row" >
                                        {!! Form::label('isp_licensese_area_thana', 'Thana/Upazila', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('isp_licensese_area_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_thana',[''=>'Select'],'', ['class' => 'form-control isp_licensese_area_thana','data-type' => '4', 'id' => 'isp_licensese_area_thana']) !!}
                                            {!! $errors->first('isp_licensese_area_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

                    {{-- Service Profile (If Applicable) --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Service Profile (If Applicable)
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <br>

                            {{--  Location of Installation --}}
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Location of Installation
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('location_of_ins_district', 'District', ['class' => 'col-md-4 ']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('location_of_ins_district') ? 'has-error' : '' }}">
                                                    {!! Form::select('location_of_ins_district', $districts, '', ['class' => 'form-control', 'id' => 'location_of_ins_district', 'onchange' => "getThanaByDistrictId('location_of_ins_district', this.value, 'location_of_ins_thana',0)"]) !!}
                                                    {!! $errors->first('location_of_ins_district', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('location_of_ins_thana', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('location_of_ins_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select('location_of_ins_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'location_of_ins_thana']) !!}
                                                    {!! $errors->first('location_of_ins_thana', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('location_of_ins_address', 'Address Line 1', ['class' => 'col-md-4 ']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('location_of_ins_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('location_of_ins_address','', ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 1', 'id' => 'location_of_ins_address']) !!}
                                                    {!! $errors->first('location_of_ins_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('location_of_ins_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('location_of_ins_address2') ? 'has-error' : '' }}">
                                                    {!! Form::text('location_of_ins_address2','', ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'location_of_ins_address2']) !!}
                                                    {!! $errors->first('location_of_ins_address2', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Number of Clients/Users of Internet --}}
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Number Of Clients/ Users Of Internet (If Applicable)
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('home', 'Home', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('home') ? 'has-error' : '' }}">
                                                    {!! Form::number('home', '', ['class' => 'form-control', 'placeholder' => 'Enter Home', 'id' => 'home']) !!}
                                                    {!! $errors->first('home', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('cyber_cafe', 'Cyber Cafe', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('cyber_cafe') ? 'has-error' : '' }}">
                                                    {!! Form::number('cyber_cafe', '', ['class' => 'form-control', 'placeholder' => 'Enter Cyber Cafe', 'id' => 'cyber_cafe']) !!}
                                                    {!! $errors->first('cyber_cafe', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('office', 'Office', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('office') ? 'has-error' : '' }}">
                                                    {!! Form::number('office', '', ['class' => 'form-control', 'placeholder' => 'Enter Office', 'id' => 'office']) !!}
                                                    {!! $errors->first('office', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('others', 'Others', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('others') ? 'has-error' : '' }}">
                                                    {!! Form::number('others', '', ['class' => 'form-control', 'placeholder' => 'Enter Cyber Cafe', 'id' => 'others']) !!}
                                                    {!! $errors->first('others', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Number of Clients/Users of Internet --}}
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Numbers And List Of Clients/ Users Of Domestic  Point To Point Data Connectivity
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('corporate_user', 'Corporate user', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('corporate_user') ? 'has-error' : '' }}">
                                                    {!! Form::number('corporate_user', '', ['class' => 'form-control', 'placeholder' => 'Enter Corporate user', 'id' => 'corporate_user']) !!}
                                                    {!! $errors->first('corporate_user', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('personal_user', 'Personal User', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('personal_user') ? 'has-error' : '' }}">
                                                    {!! Form::number('personal_user', '', ['class' => 'form-control', 'placeholder' => 'Enter Personal User', 'id' => 'personal_user']) !!}
                                                    {!! $errors->first('personal_user', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('branch_user', 'Branch User', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('branch_user') ? 'has-error' : '' }}">
                                                    {!! Form::number('branch_user', '', ['class' => 'form-control', 'placeholder' => 'Enter Branch User', 'id' => 'branch_user']) !!}
                                                    {!! $errors->first('branch_user', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                {!! Form::label('list_of_clients', 'List of Clients/ Users', ['class' => 'col-md-4 ']) !!}
                                                <div class="col-md-8 {{ $errors->has('list_of_clients') ? 'has-error' : '' }}">
                                                    {{ Form::file('list_of_clients',['class'=>'form-control input','id'=>'list_of_clients','onchange'=>"createObjUrl(event,'list_of_clients_images_base64')",])}}
                                                    <input  type="hidden" name="list_of_clients_base64" id="list_of_clients_images_base64" value="" >
                                                    <div  id="list_of_clients_images_preview"></div>
                                                    {!! $errors->first('list_of_clients', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Equipment List --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex">
                            Equipment List
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment_tbl">
                                <thead>
                                <tr class="text-center">
                                    <th width="7%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="25%" class="verticalAlignMiddle" >Equipment Name</th>
                                    <th width="20%" class="verticalAlignMiddle" >Brand & Model</th>
                                    <th width="20%" class="verticalAlignMiddle" >Quantity</th>
                                    <th width="20%" class="verticalAlignMiddle" >Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="equipment_1">
                                    <td class="d-flex justify-content-center w-60">1</td>
                                    <td><input type="text" class="form-control equipment_name" name="equipment_name[1]" placeholder="Enter equipment name" ></td>
                                    <td><input type="text" class="form-control equipment_brand_model" name="equipment_brand_model[1]" placeholder="Enter brand & model"></td>
                                    <td><input type="number" class="form-control equipment_quantity" name="equipment_quantity[1]" placeholder="Enter quantity"></td>
                                    <td><input type="text" class="form-control equipment_remarks" name="equipment_remarks[1]" placeholder="Enter remarks"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Proposed Tariff Chart --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex">
                            Proposed Tariff Chart
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="tariffChart_tbl">
                                <thead>
                                <tr class="text-center">
                                    <th width="3%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="23%" class="verticalAlignMiddle" >Packages Name/Plan</th>
                                    <th width="22%" class="verticalAlignMiddle" >Internet Bandwidth Package <br> Speed (Kbps/Mbps)</th>
                                    <th width="14%" class="verticalAlignMiddle" >Price(BDT)</th>
                                    <th width="15%" class="verticalAlignMiddle" >Duration</th>
                                    <th width="15%" class="verticalAlignMiddle" >Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="tariffChart_1" >
                                    <td class="d-flex justify-content-center w-60">1</td>
                                    <td><input type="text" class="form-control tariffChart_package_name_plan" name="tariffChart_package_name_plan[1]" placeholder="Enter packages name/ plan"></td>
                                    <td><input type="number" class="form-control tariffChart_bandwidth_package" name="tariffChart_bandwidth_package[1]" placeholder="Enter Speed (Kbps/Mbps)"></td>
                                    <td><input type="number" class="form-control tariffChart_price" name="tariffChart_price[1]" placeholder="Enter price(BDT)"></td>
                                    <td><input type="text" class="form-control tariffChart_duration" name="tariffChart_duration[1]" placeholder="Enter duration"></td>
                                    <td><input type="text" class="form-control tariffChart_remarks" name="tariffChart_remarks[1]" placeholder="Enter remarks"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Necessary attachment --}}
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'add'])

                    {{-- Declaration --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class=" !font-normal">
                                                Declaration Has any Application for License of ISP been rejected before?
                                            </label>
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Has any License of ISP issued previously to the Applicant/any Share Holder/Partner been cancelled?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the Commission?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{--                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes', 'accept'=>'image/*', 'onchange'=>"generateBase64String('if_declaration_q3_yes','declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}--}}
                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes','onchange'=>"createObjUrl(event, 'declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}
                                                <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >
                                            </div>
                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Note --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Note <span style="float: right;"></span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol style="list-style-type:disc;">
                                        <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                                        <li>Application without the submission of complete documents and information will not be accepted.</li>
                                        <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                                        <li>Fees and charges are not refundable.</li>
                                        <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Payment & Submit</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Service Fee Payment --}}
                    <div id="payment_panel"></div>

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/isp-license-issue/list/'. Encryption::encodeId(3)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>
                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                    </button>
                </div>
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn" id="save_as_draft" disabled>
                    Save as Draft
                </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>
@include('partials.image-upload')

<script>
    let isAppLimitExceeded = 0;
    $(document).ready(function() {
        getHelpText();
        if(!"{{ config('app.app_submit_by_isp_nationwide')  }}"){
            document.getElementById('type_of_isp_licensese').children[1].disabled = true;
        }

        // setIntlTelInput('.contact_mobile');
        // setIntlTelInput('.shareholder_mobile');
        // setIntlTelInput('.applicant-mobile');

        @isset($companyInfo->office_district)
        getThanaByDistrictId('reg_office_district', ' {{ $companyInfo->office_district ?? '' }}', 'reg_office_thana',{{ $companyInfo->office_thana ?? '' }})
        @endisset

        let form = $("#application_form").show();
        let popupWindow = null;
        // step part
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                let errorStatus = false;
                if (newIndex == 1) {

                    if(isAppLimitExceeded){
                        alert("The quota for this category of license in this designated area has already been filled up, hence, you are not allowed to apply for this category of license.")
                        return false;
                    }

                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total !== 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }

                    //validate client side rendered fields
                    // const requiredClientFields = document.querySelectorAll('.client-rendered-row input, .client-rendered-row select');
                    // let nextAvailable = true;
                    // for(const elem of requiredClientFields) {
                    //     if(elem.classList.contains('required') && !elem.value) {
                    //         elem.classList.add('error');
                    //         nextAvailable = false;
                    //     }
                    // }
                    // if(!nextAvailable) return false;

                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex === 2) {
                    // declarationSectionValidation();

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
                    if(currentIndex > newIndex) {
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
                if (newIndex < currentIndex) {
                    return true;
                }
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if(!form.valid()) errorStatus = true;
                if(errorStatus) return false;
                return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex > 0) {
                    form.find('#submitForm').css('display', 'none');
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex === 1) {
                    form.find('#submitForm').css('display', 'none');
                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");
                }
                else {
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
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>', 'Sample', '');--}}
            openPreviewV2();
        });

        // datepicker part
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        // declaration part
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

        //get share value and number of share
        $("#shareholderRow").on("keyup", ".share-value, .no-of-share", function(){
            calculateShareValue();
        });

        // total no. of share and total value calculation
        function calculateShareValue(){
            let totalNoOfShare = 0;
            $(".share-value").each(function(){
                totalNoOfShare +=  parseInt(this.value) || 0;
            });
            $("#total_share_value").val(totalNoOfShare);

            let totalShareValue = 0;
            $(".no-of-share").each(function(){
                totalShareValue += parseInt(this.value) || 0;
            });
            $("#total_no_of_share").val(totalShareValue);
        }

        // license type part
        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                isAppLimitExceeded = 0;
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                addEventListenerToIspType('isp_licensese_area_division')
            }

            if(this.value == 3){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control isp_licensese_area_district','data-type' => '3', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
                addEventListenerToIspType('isp_licensese_area_district')
            }

            if(this.value == 4){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control isp_licensese_area_district', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');

                addEventListenerToIspType('isp_licensese_area_thana')

            }
            getHelpText();
        });
        $(document).on('change','#isp_licensese_area_division' ,function (){
            $("#isp_licensese_area_thana").prop('selectedIndex',0);
        });

        // copnay type part
        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });
        let company_type = "{{$companyInfo->org_type}}";
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

        // add row part
        $('.add_row').click(function(){
            var btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,

                },
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled",false);
                    getHelpText();
                }
            });
        });
        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // payment panel part
        $("#type_of_isp_licensese").change(function() {
            let oss_fee = 0;
            let vat = 0;
            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",
                data: {
                    process_type_id: {{ $process_type_id }},
                    payment_type: 1,
                    license_type: $(this).val()
                },
                success: function(response) {
                    if(response.responseCode == -1){
                        alert(response.msg);
                        return false;
                    }
                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function() {
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0,
                        7: 0,
                        8: 0,
                        9: 0,
                        10: 0
                    };
                    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        });
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
    });

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

    // preview
    function openPreview() {
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }
    function openPreviewV2() {
        let preview = 1;
        // If select online payment
        const onlinePayment = "{{config('payment.online_payment')}}";
        if ($("#online_payment").is(':checked') && !onlinePayment) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }

        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }

        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function deleteContactRow(element) {
        element.remove();
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

        if($("#declaration_q3_yes").is(":checked") && $("#if_declaration_q3_yes").val() === ""){
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }

        return error_status;
    }
    function SetErrorInShareOfInputField(){
        $( ".shareholder_share_of" ).each(function( index ) {
            $(this).addClass('error');
        });
    }

    {{--    // payemnt--}}
    {{--    $(document).ready(function() {--}}
    {{--        $(document).on('click','.addPayOrderRow',function(){--}}
    {{--            let lastRowId = document.querySelectorAll(".single_pay_order").length;--}}
    {{--            lastRowId += 1--}}
    {{--            $('#pay_order_information').append(`<div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_${lastRowId}">--}}
    {{--                <div class="card-header">--}}
    {{--                    Pay Order Information--}}
    {{--                    <span class="btn btn-danger cross-button" style="float: right; cursor: pointer;"  onclick="removePayOrder(${lastRowId})" >--}}
    {{--                        <i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i>--}}
    {{--                </span>--}}
    {{--                </div>--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="row form-group">--}}
    {{--                        <div class="col-md-6 pay_order_fields" style="display: block;">--}}
    {{--                            <div class="row">--}}
    {{--                                <label for="pay_order_copy_${lastRowId}" class="col-md-5 text-left required-star">Pay Order Copy</label>--}}
    {{--                                                                    <div class="col-md-7" id="pay_order_copy_preview">--}}
    {{--                                        <input type="file" style="border: none;" class="form-control input-md require_check required" name="pay_order_copy[${lastRowId}]" id="pay_order_copy_${lastRowId}" onchange="createObjUrl(event, 'pay_order_copy_url_base64_${lastRowId}')" required="">--}}
    {{--                                        <input type="hidden" id="pay_order_copy_url_base64_${lastRowId}">--}}
    {{--                                    </div>--}}
    {{--                                                            </div>--}}
    {{--                        </div>--}}

    {{--                        <div class="col-md-6">--}}
    {{--                            <div class="row">--}}
    {{--                                <label for="pay_order_number_${lastRowId}" class="col-md-5 text-left required-star">Pay Order Number</label>--}}
    {{--                                <div class="col-md-7">--}}
    {{--                                    <input class="form-control input-md require_check required" id="pay_order_number_${lastRowId}" name="pay_order_number[${lastRowId}]" type="number" value="" required="">--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                    </div>--}}
    {{--                    <div class="pay_order_fields" style="display: block;">--}}
    {{--                            <div class="row form-group">--}}
    {{--                                <div class="col-md-6">--}}
    {{--                                    <div class="row">--}}
    {{--                                        <label for="pay_order_date_${lastRowId}" class="col-md-5 text-left required-star">Pay Order Date</label>--}}
    {{--                                        <div class="col-md-7" id="pay_order_date_preview">--}}
    {{--                                            <div class="input-group date datetimepicker_pay_order" id="datetimepicker_pay_order_${lastRowId}" data-target-input="nearest">--}}
    {{--                                                <input class="form-control input-md require_check required" data-target="#datetimepicker_pay_order_${lastRowId}" data-toggle="datetimepicker" id="pay_order_date_${lastRowId}" placeholder="Enter pay order date" name="pay_order_date[${lastRowId}]" type="text" value="" required="">--}}
    {{--                                                <div class="input-group-append" data-target="#datetimepicker_pay_order_${lastRowId}" data-toggle="datetimepicker">--}}
    {{--                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>--}}
    {{--                                                </div>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="col-md-6">--}}
    {{--                                    <div class="row">--}}
    {{--                                        <label for="bank_name_${lastRowId}" class="col-md-5 text-left required-star">Bank Name</label>--}}
    {{--                                        <div class="col-md-7">--}}
    {{--{!! Form::select('bank_name[${lastRowId}]', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check', 'id' => 'bank_name_'.'${lastRowId}', 'onchange' => 'getBranchByBankId("bank_name_${lastRowId}",this.value, "branch_name_${lastRowId}")']) !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}

    {{--<div class="row form-group">--}}
    {{--    <div class="col-md-6">--}}
    {{--        <div class="row">--}}
    {{--            <label for="branch_name_${lastRowId}" class="col-md-5 text-left required-star">Branch Name</label>--}}
    {{--                                        <div class="col-md-7">--}}
    {{--                                            <select class="form-control input-md require_check required" id="branch_name_${lastRowId}" name="branch_name[${lastRowId}]" required=""><option value="">Select Branch Name</option></select>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                                    </div>--}}
    {{--            </div>`);--}}

    {{--            var today = new Date();--}}
    {{--            var yyyy = today.getFullYear();--}}
    {{--            jQuery('.datetimepicker_pay_order').datetimepicker({--}}
    {{--                format: 'DD-MMM-YYYY',--}}
    {{--                maxDate: 'now',--}}
    {{--                minDate: '01/01/' + (yyyy - 110),--}}
    {{--                ignoreReadonly: true,--}}
    {{--            });--}}
    {{--        })--}}
    {{--    })--}}
    {{--    --}}
    {{--    --}}
    {{--    function removePayOrder(rowId){--}}
    {{--        $("#single_pay_order_"+rowId).remove();--}}
    {{--    }--}}

    const ispLicenseAreaListeners = {
        'isp_licensese_area_division' : 0,
        'isp_licensese_area_district' : 0,
        'isp_licensese_area_thana' : 0,
    };
    function addEventListenerToIspType(selector) {
        const element = document.getElementById(selector);
        if(!ispLicenseAreaListeners[selector]){
            element.addEventListener("change", function(event) {
                const ispLicenseType = document.getElementById('type_of_isp_licensese').value;
                const currentIspLicenseType = event.target.dataset.type;
                const ISP_LICENSE_TYPES = {
                    2: 'isp_licensese_area_division',
                    3: 'isp_licensese_area_district',
                    4: 'isp_licensese_area_thana',
                };
                let areaId =  ISP_LICENSE_TYPES[ispLicenseType] ? document.getElementById(ISP_LICENSE_TYPES[ispLicenseType]).value : '';

                if(!areaId || (ispLicenseType != currentIspLicenseType)){
                    return
                }
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp-license-issue/check-application-limit') }}",
                    data: {ispLicenseType,areaId},
                    success: function(response) {
                        // error
                        if(parseInt(response.responseCode) == 2){
                            alert(response.message);
                            return false;
                        }

                        isAppLimitExceeded = parseInt(response.responseCode);
                        if(isAppLimitExceeded) alert("The quota for this category of license in this designated area has already been filled up, hence, you are not allowed to apply for this category of license.");

                    },
                    error: function(error){
                        alert(error)
                    }
                });
            });
            ispLicenseAreaListeners[selector] = 1;
        }

    }

</script>
