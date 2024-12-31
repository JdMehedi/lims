<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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
        width: 33.2% !important;
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

    #renewForm input[type=checkbox]{
        vertical-align: bottom;
        width: 15px;
        height: 15px;
    }
    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-md-12 col-lg-12" id="renewForm">
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application For Internet Service Provider (ISP) License Renewal</h4>
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
                <div style="display: none;" id="pcsubmitadd"></div>
                {{--    {!! Form::hidden('company_id', $companyInfo->id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}--}}
                <h3>Basic Information</h3>
                {{--    <br>--}}
                <fieldset>
                    {!! Form::hidden('form_version', 'v2', ['class' => 'form-control input-md required', 'id' => 'form_version']) !!}
                    @includeIf('common.subviews.licenseInfo', [ 'mode' => 'renew-serarch', 'url' => 'isp-license-renew/fetchAppData'])

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
                                            {!! Form::select('isp_licensese_area_division', $division, '', ['class' => 'form-control isp_licensese_area_division', 'id' => 'isp_licensese_area_division', 'onchange' => "getDistrictByDivisionId('isp_licensese_area_division', this.value, 'isp_licensese_area_district',0)"]) !!}
                                            {!! $errors->first('isp_licensese_area_division', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="district" style="display: none;"></div>
                                <div class="col-md-6" id="thana" style="display: none;">
                                    <div class="form-group row" >
                                        {!! Form::label('isp_licensese_area_thana', 'Thana/Upazila', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('isp_licensese_area_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_thana',[''=>'Select'],'', ['class' => 'form-control isp_licensese_area_thana', 'id' => 'isp_licensese_area_thana']) !!}
                                            {!! $errors->first('isp_licensese_area_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

                    {{--         Service Profile (If Applicable) --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Service Profile (If Applicable)
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <br>

                            {{--                                      Location of Installation--}}
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

                            {{--                                     Number of Clients/Users of Internet--}}
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

                            {{--                                     Number of Clients/Users of Internet--}}
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

                    {{-- Equipment List --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex">
                            Proposed Equipment List
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment">
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
                                    <td><input type="text" style="text-align: center;" class="form-control equipment_name" name="equipment_name[1]" placeholder="Enter equipment name" ></td>
                                    <td><input type="text"  style="text-align: center;" class="form-control equipment_brand_model" name="equipment_brand_model[1]" placeholder="Enter brand & model"></td>
                                    <td><input type="number" style="text-align: center;" class="form-control equipment_quantity" name="equipment_quantity[1]" placeholder="Enter quantity"></td>
                                    <td><input type="text" style="text-align: center;" class="form-control equipment_remarks" name="equipment_remarks[1]" placeholder="Enter remarks"></td>
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
                            <table class="table table-bordered" id="tariffChart">
                                <thead>
                                <tr class="text-center">
                                    <th width="3%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="23%" class="verticalAlignMiddle" >Packages Name/Plan</th>
                                    <th width="22%" class="verticalAlignMiddle" >Internet Bandwidth Package <br> Speed (Kbps/Mbps)</th>
                                    <th width="14%" class="verticalAlignMiddle" >Price(BDT)</th>
                                    <th width="15%" class="verticalAlignMiddle" >Duration(in Days)</th>
                                    <th width="15%" class="verticalAlignMiddle" >Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="tariffChart_1" >
                                    <td class="d-flex justify-content-center w-60">1</td>
                                    <td><input type="text" style="text-align: center;" class="form-control tariffChart_package_name_plan" name="tariffChart_package_name_plan[1]" placeholder="Enter packages name/ plan"></td>
                                    <td><input type="number" style="text-align: center;" class="form-control tariffChart_bandwidth_package" name="tariffChart_bandwidth_package[1]" placeholder="Enter Speed (Kbps/Mbps)"></td>
                                    <td><input type="number" style="text-align: center;" class="form-control tariffChart_price" name="tariffChart_price[1]" placeholder="Enter price(BDT)"></td>
                                    <td><input type="text" style="text-align: center;" class="form-control tariffChart_duration" name="tariffChart_duration[1]" placeholder="Enter duration"></td>
                                    <td><input type="text" style="text-align: center;" class="form-control tariffChart_remarks" name="tariffChart_remarks[1]" placeholder="Enter remarks"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Technical Profile --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Technical Profile
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12 border border-header-box" id="type_of_service_section">
                                    <span class="border-header-txt">Type of Service</span>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="fth">
                                            <input class="form-check-input" name="service_type[]" type="checkbox" id="fth" value="fth">
                                            FTTH
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="dsl">
                                            <input class="form-check-input" name="service_type[]" type="checkbox" id="dsl" value="dsl">
                                            DSL
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="adsl">
                                            <input class="form-check-input" name="service_type[]" type="checkbox" id="adsl" value="adsl">
                                            ADSL
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="vdsl">
                                            <input class="form-check-input" name="service_type[]" type="checkbox" id="vdsl" value="vdsl">
                                            VDSL
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 border border-header-box">
                                    <span class="border-header-txt">Wired</span>
                                    <div class="form-group row">
                                        {!! Form::label('cable_length', 'Length of laid cable (Km)', ['class' => 'col-md-4 ']) !!}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4" id="cable_type_section">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label" for="optical_fiber">
                                                    <input class="form-check-input" name="cable_type[]" type="checkbox" id="optical_fiber" value="optical_fiber">
                                                    Optical Fiber
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label" for="utp">
                                                    <input class="form-check-input" name="cable_type[]" type="checkbox" id="utp" value="utp">
                                                    UTP
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label" for="stp">
                                                    <input class="form-check-input" name="cable_type[]" type="checkbox" id="stp" value="stp">
                                                    STP
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('cable_length') ? 'has-error' : '' }}">
                                            {!! Form::number('cable_length', '', ['class' => 'form-control', 'placeholder' => 'Enter length of laid cable (Km)', 'id' => 'cable_length']) !!}
                                            {!! $errors->first('cable_length', '<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-2" ></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 border border-header-box">
                                    <span class="border-header-txt">Bandwidth</span>
                                    <table class="table table-bordered" id="bandwidth_tbl">
                                        <thead>
                                        <tr>
                                            <th width="7%" class="text-center">SL No.</th>
                                            <th width="25%" class="text-center">Name of the primary IIG</th>
                                            <th width="20%" class="text-center">Allocation</th>
                                            <th width="20%" class="text-center">Up Stream</th>
                                            <th width="20%" class="text-center">Down Stream</th>
                                            <th width="8%" class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr row_id="bandwidth_1">
                                            <td class="d-flex justify-content-center">1</td>
                                            <td><input type="text" class="form-control" name="bandwidth_primary_iig[]" placeholder="Enter Name of the primary IIG"></td>
                                            <td><input type="number" class="form-control" name="bandwidth_allocation[]" placeholder="Enter Allocation"></td>
                                            <td><input type="number" class="form-control" name="bandwidth_up_stream[]" placeholder="Enter Up Stream"></td>
                                            <td><input type="number" class="form-control" name="bandwidth_down_stream[]" placeholder="Enter Down Stream"></td>
                                            <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Disaster Recovery Centre / Data Centre --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Disaster Recovery Centre/ Data Centre
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12 border border-header-box">
                                    <span class="border-header-txt">Address of Installation</span>
                                    {{ Form::textarea('installation_address', '', array('class' =>'form-control input', 'placeholder'=>'Enter Address of Installation','cols' => 5, 'rows' =>5, '' => ''))}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 border border-header-box">
                                    <span class="border-header-txt">Connectivity</span>
                                    <table class="table table-bordered" id="connectivity">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" width="8%" style="text-align: center; vertical-align: middle">SL No.</th>
                                            <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Connectivity Provider</th>
                                            <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">Allocation</th>
                                            <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">Frequency</th>
                                            <th width="8%" rowspan="2" style="text-align: center; vertical-align: middle">Action</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center; vertical-align: middle">Up Stream</th>
                                            <th style="text-align: center; vertical-align: middle">Down Stream</th>
                                            <th style="text-align: center; vertical-align: middle">Up</th>
                                            <th style="text-align: center; vertical-align: middle">Down</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr row_id="connectivity_1">
                                            <td class="d-flex justify-content-center">1</td>
                                            <td><input type="text" class="form-control" name="connectivity_provider[]" placeholder="Enter Connectivity provider"></td>
                                            <td><input type="number" class="form-control" name="connectivity_up_stream[]" placeholder="Enter Up Stream"></td>
                                            <td><input type="number" class="form-control" name="connectivity_down_stream[]" placeholder="Enter Down Stream"></td>
                                            <td><input type="number" class="form-control" name="connectivity_up_frequency[]" placeholder="Enter Up"></td>
                                            <td><input type="number" class="form-control" name="connectivity_down_frequency[]" placeholder="Enter Down"></td>
                                            <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--    <br>--}}
                <fieldset>
                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Required Documents
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

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
                                                Has any Application for License of ISP been rejected before?
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
                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes', 'accept'=>'image/*', 'onchange'=>"createObjUrl(event, 'declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}
                                                <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >
                                            </div>
                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/ terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
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
                                    <ol style="list-style: disc !important;">
                                        <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/ fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                                        <li>Application without the submission of complete documents and information will not be accepted.</li>
                                        <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                                        <li>Fees and charges are not refundable.</li>
                                        <li>The Commission is entitled to change this from time to time if necessary.</li>
                                        <li>Updated documents shall be submitted during application.</li>
                                        <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Payment & Submit</h3>
                {{--    <br>--}}
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
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','id'=>'termsCheck','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('/isp-license-renew/list/'. Encryption::encodeId(2)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    {{--        @if(!in_array($appInfo->status_id,[5]))--}}
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                            class="btn btn-success btn-md" value="submit" onclick="openPreviewV2()" name="actionBtn">Submit
                    </button>
                    {{--        @endif--}}

                    {{--        @if($appInfo->status_id == 5)--}}
                    {{--            <button type="submit" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"--}}
                    {{--                    class="btn btn-info btn-md"--}}
                    {{--                    value="Re-submit" name="actionBtn">Re-submit--}}
                    {{--            </button>--}}
                    {{--        @endif--}}
                </div>

                {{--    @if(!in_array($appInfo->status_id,[5]))--}}
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                        name="actionBtn" id="save_as_draft">Save as Draft
                </button>
                {{--    @endif--}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>
    getHelpText();
  // setIntlTelInput('.shareholder_mobile');
  //   setIntlTelInput('.contact_mobile');
    // setIntlTelInput('.applicant-mobile');

{{--    @isset($companyInfo->office_district)--}}
{{--    getThanaByDistrictId('applicant_district', ' {{ $companyInfo->office_district ?? '' }}', 'applicant_thana',{{ $companyInfo->office_thana ?? '' }})--}}
{{--    @endisset--}}

    var selectCountry = '';
    $(document).ready(function() {

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

        // add contact person
{{--        $(".addContactPersonRow").on('click', function() {--}}
{{--            let lastRowId = $('#contactPersonRow .card:last').attr('id').split('_')[1];--}}
{{--            let updateRowId = parseInt(lastRowId)+1;--}}
{{--            // console.log(lastRowId);--}}
{{--            // return false;--}}
{{--            $("#contactPersonRow").append(`--}}
{{--                <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_${updateRowId}">--}}
{{--                        <div class="card-header">--}}
{{--                            Contact Person Information--}}
{{--                            <span style="float: right; cursor: pointer;">--}}
{{--                                 <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--            <div class="card-body" style="padding: 5px 0px 0px;">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('contact_person_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">--}}
{{--                                    {!! Form::text('contact_person_name[${updateRowId}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--<div class="form-group row">--}}
{{--{!! Form::label('contact_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">--}}
{{--            {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation',  'placeholder' => 'Enter Designation', 'id' => 'contact_designation_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="row">--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">--}}
{{--            {!! Form::text('contact_mobile[${updateRowId}]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_person_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">--}}
{{--            {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


{{--<div class="row">--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_website_${updateRowId}', 'Website', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">--}}
{{--            {!! Form::text('contact_website[${updateRowId}]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Enter Website', 'id' => 'contact_website_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_district_${updateRowId}', 'District', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">--}}
{{--                        {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_'.'${updateRowId}', 'onchange' => 'getThanaByDistrictId("contact_district_${updateRowId}",this.value, "contact_thana_${updateRowId}",0)']) !!}--}}
{{--            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="row">--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">--}}
{{--            {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.'${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-md-6">--}}
{{--        <div class="form-group row">--}}
{{--{!! Form::label('contact_person_address_${updateRowId}', 'Address', ['class' => 'col-md-4']) !!}--}}
{{--            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">--}}
{{--            {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${updateRowId}']) !!}--}}
{{--            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--`);--}}
{{--            getHelpText();--}}
{{--          // setIntlTelInput('.contact_mobile');--}}
{{--        });--}}

        //add shareholder row
    {{--    $(".addShareholderRow").on('click', function() {--}}
    {{--        let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);--}}
    {{--        let updateRowId = parseInt(lastRowId)+1;--}}
    {{--        $('#shareholderRow').append(--}}
    {{--            `<tr id="R_${updateRowId}" class="client-rendered-row">--}}
    {{--<td>--}}
    {{--<div class="card card-magenta border border-magenta">--}}
    {{--                                        <div class="card-header">--}}
    {{--                                            Shareholder/partner/proprietor Details Information--}}
    {{--                                            <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
    {{--                                                 <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
    {{--                                            </span>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="card-body" style="padding: 15px 25px;">--}}

    {{--<div class="row">--}}
    {{--    <div class="col-md-6">--}}
    {{--        <div class="form-group row">--}}
    {{--            {!! Form::label('shareholder_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_name[${updateRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name',  'id' => 'shareholder_name_${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row">--}}
    {{--            {!! Form::label('shareholder_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_designation[${updateRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row">--}}
    {{--            {!! Form::label('shareholder_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_email[${updateRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row">--}}
    {{--            {!! Form::label('shareholder_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_mobile[${updateRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row">--}}
    {{--            {!! Form::label('shareholder_share_of_${updateRowId}', '% of share', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::number('shareholder_share_of[${updateRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row" id="nidBlock_${updateRowId}" style="display: none;">--}}
    {{--            {!! Form::label('shareholder_nid_${updateRowId}', 'NID No', ['class' => 'col-md-4 ']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_nid[${updateRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.'${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row" id="passportBlock_${updateRowId}" style="display: none;">--}}
    {{--            {!! Form::label('shareholder_passport_${updateRowId}', 'Passport No.', ['class' => 'col-md-4 ']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::text('shareholder_passport[${updateRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${updateRowId}']) !!}--}}
    {{--            {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--<div class="col-md-6">--}}
    {{--        <div class="form-group row" style="margin-bottom:45px!important;">--}}
    {{--            {!! Form::label('correspondent_photo_${updateRowId}', 'Image', ['class' => 'col-md-4 required-star']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}
    {{--                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
    {{--                    <div class="col-md-8">--}}
    {{--                        <input type="file" class="form-control input-sm correspondent_photo"--}}
    {{--                        style="border: none; margin-bottom: 5px;"--}}
    {{--                               name="correspondent_photo[${updateRowId}]" id="correspondent_photo_${updateRowId}" size="300x300"--}}
    {{--                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${updateRowId}', 'correspondent_photo_base64_${updateRowId}')" />--}}

    {{--                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
    {{--                                          [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]--}}
    {{--                                        <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
    {{--                                    </span>--}}
    {{--                    </div>--}}
    {{--                    <div class="col-md-4">--}}
    {{--                        <label class="center-block image-upload" for="correspondent_photo_${updateRowId}">--}}
    {{--                            <figure>--}}
    {{--                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
    {{--                                     src="{{asset('assets/images/demo-user.jpg') }}"--}}
    {{--                                     class="img-responsive img-thumbnail" id="correspondent_photo_preview_${updateRowId}" />--}}
    {{--                            </figure>--}}
    {{--                            <input type="hidden" id="correspondent_photo_base64_${updateRowId}" name="correspondent_photo_base64[${updateRowId}]" />--}}
    {{--                        </label>--}}
    {{--                    </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        </div>--}}
    {{--        <div class="form-group row" style="margin-top:10px;">--}}
    {{--            {!! Form::label('shareholder_dob_${updateRowId}', 'Date of Birth', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">--}}
    {{--            <div class="input-group date datetimepicker4" id="dob${lastRowId}" data-target-input="nearest">--}}
    {{--                    {!! Form::text('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${updateRowId}', 'placeholder' => 'Enter Date Of Birth']) !!}--}}
    {{--            <div class="input-group-append" data-target="#dob${lastRowId}" data-toggle="datetimepicker">--}}
    {{--                        <div class="input-group-text">--}}
    {{--                            <i class="fa fa-calendar"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="form-group row">--}}
    {{--{!! Form::label('shareholder_nationality_${updateRowId}', 'Nationality', ['class' => 'col-md-4']) !!}--}}
    {{--            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">--}}
    {{--                {!! Form::select('shareholder_nationality[${updateRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.'${lastRowId+1}']) !!}--}}
    {{--            {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</td>--}}
    {{--</tr>`);--}}

    {{--        $("#shareholderDataCount").val(lastRowId+1);--}}
    {{--        var today = new Date();--}}
    {{--        var yyyy = today.getFullYear();--}}

    {{--        $('.datetimepicker4').datetimepicker({--}}
    {{--            format: 'DD-MMM-YYYY',--}}
    {{--            maxDate: 'now',--}}
    {{--            minDate: '01/01/' + (yyyy - 110),--}}
    {{--            ignoreReadonly: true,--}}
    {{--        });--}}
    {{--        getHelpText();--}}
    {{--     //   setIntlTelInput('.shareholder_mobile');--}}
    {{--    });--}}
    {{--    $('#shareholderRow').on('click', '.shareholderRow', function () {--}}
    {{--        let prevDataCount = $("#shareholderDataCount").val();--}}

    {{--        var child = $(this).closest('tr').nextAll();--}}

    {{--        child.each(function () {--}}
    {{--            var id = $(this).attr('id');--}}
    {{--            var idx = $(this).children('.row-index').children('p');--}}
    {{--            var dig = parseInt(id.substring(1));--}}
    {{--            idx.html(`Row ${dig - 1}`);--}}
    {{--            $(this).attr('id', `R${dig - 1}`);--}}
    {{--        });--}}
    {{--        $(this).closest('tr').remove();--}}
    {{--        rowId--;--}}
    {{--        $("#shareholderDataCount").val(prevDataCount - 1);--}}
    {{--    });--}}

        $('.add_row').click(function(){
            var btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/add-row') }}",
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

    });
</script>

<script>
    $(document).ready(function() {
        getHelpText();
{{--        @isset($appInfo->org_district)--}}
{{--        getThanaByDistrictId('applicant_district', {{ $appInfo->org_district ?? '' }},--}}
{{--            'applicant_thana', {{ $appInfo->org_upazila ?? '' }});--}}
{{--        @endisset--}}

{{--        @isset($contact_person)--}}
{{--        @foreach($contact_person as $index => $person)--}}
{{--        getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},--}}
{{--            'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});--}}
{{--        @endforeach--}}
{{--        @endisset--}}


{{--        @isset($appInfo->isp_license_division)--}}
{{--        getDistrictByDivisionId('isp_licensese_area_division',--}}
{{--            {{ $appInfo->isp_license_division ?? '' }},--}}
{{--            'isp_licensese_area_district', {{ $appInfo->isp_license_district ?? '' }});--}}
{{--        @endisset--}}

{{--        @isset($appInfo->isp_license_district)--}}
{{--            getThanaByDistrictId('isp_licensese_area_district', {{ $appInfo->isp_license_district ?? '' }},--}}
{{--            'isp_licensese_area_thana', {{ $appInfo->isp_license_upazila ?? '' }});--}}
{{--        @endisset--}}


{{--        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 1)--}}
{{--            $('#division').css('display','none');--}}
{{--            $('#district').css('display','none');--}}
{{--            $('#thana').css('display','none');--}}
{{--        @endif--}}

{{--        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 2)--}}
{{--            $('#division').css('display','inline');--}}
{{--            $('#district').css('display','none');--}}
{{--            $('#thana').css('display','none');--}}
{{--        @endif--}}

{{--        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 3)--}}
{{--            $('#division').css('display','inline');--}}
{{--            $('#district').css('display','inline');--}}
{{--            $('#thana').css('display','none');--}}
{{--        @endif--}}

{{--        @if(!empty($appInfo->isp_license_type) && $appInfo->isp_license_type == 4)--}}
{{--            $('#division').css('display','inline');--}}
{{--            $('#district').css('display','inline');--}}
{{--            $('#thana').css('display','flex');--}}
{{--        @endif--}}

        // $('#type_of_isp_licensese').on('change', function () {
        //     if(this.value == 1 || this.value == ""){
        //         $('#division').css('display','none');
        //         $('#district').css('display','none');
        //         $('#thana').css('display','none');
        //     }
        //     if(this.value == 2){
        //         $('#division').css('display','inline');
        //         $('#district').css('display','none');
        //         $('#thana').css('display','none');
        //     }
        //
        //     if(this.value == 3){
        //         $('#division').css('display','none');
        //         $('#district').css('display','inline');
        //         $('#thana').css('display','none');
        //     }
        //
        //     if(this.value == 4){
        //         $('#division').css('display','none');
        //         $('#district').css('display','inline');
        //         $('#thana').css('display','inline');
        //     }
        // });
        //nationality part
        $(document).on('change','.shareholder_nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#passportBlock_'+lastRowId).hide();

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).show();
            }
        });

        // license type part
        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').removeClass('required');
                // $('#isp_licensese_area_district').removeClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
            }

            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').removeClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
            }

            if(this.value == 3){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control isp_licensese_area_district', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').addClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
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
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').addClass('required');
                // $('#isp_licensese_area_thana').addClass('required');
            }

            getHelpText();

            let oss_fees = 0;
            let vats = 0;
            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-renew/get-payment-data-by-license-type') }}",
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

                    oss_fees = parseFloat(response.data.oss_fee);
                    vats = parseInt(response.data.vat);
                },
                complete: function() {
                    {{--console.log({{ $process_type_id }});--}}
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fees,
                        3: 0,
                        4: 0,
                        5: vats,
                        6: 0
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

        $('#company_type').on('change', function () {
            if (this.value == "") {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I/We');
            } else if (this.value == 1) {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I');
            } else {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('We');
            }
        });


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


        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // $("#type_of_isp_licensese").change(function() {
        @if(!empty($appInfo->isp_license_type))
            {{--var total_investment = $('#total_investment').val();--}}
            {{--var vat_percentage = parseFloat('{{ $vat_percentage }}');--}}

            let oss_fee = 0;
            let vat = 0;

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-renew/get-payment-data-by-license-type') }}",
                data: {
                    process_type_id: {{ $process_type_id }},
                    payment_type: 1,
                    license_type: {{$appInfo->isp_license_type}}
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
                    {{--console.log({{ $process_type_id }});--}}
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0
                    };
                    loadPaymentPanel('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        @endif
        // });
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

    function openPreview() {

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').focus()
            $('#accept_terms').addClass('error');
            return false;
        }
        $('#accept_terms').removeClass('error');
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
    function deleteContactRow(element) {
        element.remove();
    }
</script>

