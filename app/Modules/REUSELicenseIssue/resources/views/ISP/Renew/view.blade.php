<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/stylesheets/custom.min.css') }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.css") }}"/>
<style>
    label {
        font-weight: normal;
        font-size: 14px;
    }

    span {
        font-size: 14px;
    }

    .section_head {
        font-size: 24px;
        font-weight: 400;
        margin-top: 25px;
    }

    @media (min-width: 767px) {
        .addressField {
            width: 79.5%;
            float: right
        }
    }

    @media (max-width: 480px) {
        .section_head {
            font-size: 20px;
            font-weight: 400;
            margin-top: 5px;
        }

        label {
            font-weight: normal;
            font-size: 13px;
        }

        span {
            font-size: 13px;
        }

        .panel-body {
            padding: 10px 0 !important;
        }

        .form-group {
            margin: 0;
        }

        .image_mobile {
            width: 100%;
        }
    }

    .table-responsive {
        display: inline-table!important;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .border-header-box{
        padding: 25px 10px 0px;
        margin-bottom: 30px;
    }
    .border-header-txt{
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>


<div id="paymentPanel"></div>

<div class="card" style="border-radius: 10px;" id="applicationForm">
    {!! Form::open([
        'url' => url('isp-license-renew/store'),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
        'onSubmit' => 'enablePath()',
    ]) !!}
    @csrf

    <fieldset>
        {{--Addons form data--}}
        @if(in_array($appInfo->status_id, [2,5,15, 16, 50, 51, 52, 53, 54, 55, 56, 57, 60, 62, 63, 64, 65, 25, 17, 18, 19, 20, 22, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Related Reports
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    @if(isset($appInfo->dd_file_1))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_2))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission Meeting Minutes</a>
                    @endif
                    @if(isset($appInfo->dd_file_3))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry Approval  </a>
                    @endif
                    @if(isset($appInfo->shortfall_reason))
                        <a class="btn btn-primary m-1" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
                    @endif
                    @if(isset($latter[1]))
                    <a class="btn btn-primary" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
                    @endif
                    @if(isset($latter[2]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
                    @endif
                    @if(isset($latter[4]))
                    <a class="btn btn-primary m-1
                    " target="_blank" href="{{url($latter[4])}}">BG Payment Letter</a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Company Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view' , 'extra' => ['address2']])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])

        {{-- Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])


            {{-- Types of ISP License Applied for --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">Types Of ISP License Applied for</div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('type_of_isp_licensese', 'Types Of ISP License', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                @if($appInfo->isp_license_type == 1)
                                    <span>: Nationwide</span>
                                @elseif($appInfo->isp_license_type == 2)
                                    <span>: Divisional</span>
                                @elseif($appInfo->isp_license_type == 3)
                                    <span>: District</span>
                                @elseif($appInfo->isp_license_type == 4)
                                    <span>: Thana/Upazila</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="division" style="display: none;">
                        <div class="form-group row">
                            {!! Form::label('isp_licensese_area_division', 'Division', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8"><span>: {{ $appInfo->isp_license_division }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" id="district" style="display: none;">
                        <div class="form-group row">
                            {!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8"><span>: {{ $appInfo->isp_license_district }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6" id="thana" style="display: none;" >
                        <div class="form-group row"  >
                            {!! Form::label('isp_licensese_area_thana', 'Thana', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->isp_license_upazila }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])

        {{-- Shareholder/partner/proprietor Details --}}
{{--        <div class="card card-magenta border border-magenta">--}}
{{--            <div class="card-header">Shareholder/ Partner/ Proprietor Details dsgdsfhfg</div>--}}
{{--            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                @foreach($appShareholderInfo as $shareholderInfo)--}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Shareholder Information--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->name }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        @if($shareholderInfo->nationality == 18)--}}
{{--                                            {!! Form::label('shareholder_nid', 'National ID No', ['class' => 'col-md-4 ']) !!}--}}
{{--                                            <div class="col-md-8">--}}
{{--                                                <span>: {{ $shareholderInfo->nid }}</span>--}}
{{--                                            </div>--}}
{{--                                        @else--}}
{{--                                            {!! Form::label('shareholder_nid', 'Passport No.', ['class' => 'col-md-4 ']) !!}--}}
{{--                                            <div class="col-md-8">--}}
{{--                                                <span>: {{ $shareholderInfo->passport }}</span>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->designation }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->mobile }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->email }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->dob }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_image', 'Images', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}
{{--                                            <label class="center-block image-upload" for="correspondent_photo_1">--}}
{{--                                                <figure>--}}
{{--                                                    <img style="height: 99px; width: 95px; border: 1px solid #EBEBEB;"--}}
{{--                                                         src="{{$shareholderInfo->image !=""? asset($shareholderInfo->image):asset('assets/images/demo-user.jpg') }}"--}}
{{--                                                         class="img-responsive img-thumbnail"/>--}}
{{--                                                </figure>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 ']) !!}--}}
{{--                                        <div class="col-md-8"><span>: {{ $shareholderInfo->share_percent }}</span></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}

        {{-- Service Profile (If Applicable) --}}
        <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Service Profile (If Applicable)
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <br>

{{--                          Location of Installation --}}
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
                                                class="col-md-8">
                                                <span>: {{ $appInfo->location_of_ins_district_en }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('location_of_ins_thana', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
                                            <div
                                                class="col-md-8">
                                                <span>: {{ $appInfo->location_of_ins_thana_en }}</span>
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
                                                <span>: {{ $appInfo->location_of_ins_address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('location_of_ins_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                            <div
                                                class="col-md-8 {{ $errors->has('location_of_ins_address2') ? 'has-error' : '' }}">
                                                <span>: {{ $appInfo->location_of_ins_address2 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

{{--                         Number of Clients/Users of Internet --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Number Of Clients/ Users Of Internet (If Applicable)
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('home', 'Home', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->home }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('cyber_cafe', 'Cyber Cafe', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->cyber_cafe }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('office', 'Office', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->office }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('others', 'Others', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('others') ? 'has-error' : '' }}">
                                                <span>: {{ $appInfo->others }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

{{--                         Number of Clients/Users of Internet --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Numbers And List Of Clients/ Users Of Domestic  Point To Point Data Connectivity
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('corporate_user', 'Corporate user', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                <span>: {{ $appInfo->corporate_user }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('personal_user', 'Personal User', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('personal_user') ? 'has-error' : '' }}">
                                                <span>: {{ $appInfo->personal_user }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('branch_user', 'Branch User', ['class' => 'col-md-4 ']) !!}
                                            <div class="col-md-8 {{ $errors->has('branch_user') ? 'has-error' : '' }}">
                                                <span>: {{ $appInfo->branch_user }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('list_of_clients', 'List of Clients/ Users', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('list_of_clients') ? 'has-error' : '' }}">
                                                <a class="btn btn-file" target="_blank" href="{{asset($appInfo->list_of_clients)}}"><i class="fa fa-file"></i> View Document</a>
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
            <div class="card-header">Equipment List</div>
            <div class="card-body" style="padding: 15px 25px;">
                <table class="table table-bordered" id="equipment_tbl">
                    <thead>
                    <tr class="text-center">
                        <th width="7%" class="text-center">SL No.</th>
                        <th width="25%">Equipment Name</th>
                        <th width="20%">Brand & Model</th>
                        <th width="20%">Quantity</th>
                        <th width="20%">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($isp_equipment_list) && count($isp_equipment_list) > 0)
                        @foreach($isp_equipment_list as $index=>$value)
                            <tr row_id="equipment_{{$index+1}}">
                                <td style="text-align: center;">{{$index+1}}</td>
                                <td style="text-align: center;"> {{ $value->name }} </td>
                                <td style="text-align: center;"> {{ $value->brand_model }} </td>
                                <td style="text-align: center;"> {{ $value->quantity }} </td>
                                <td style="text-align: center;"> {{ $value->remarks }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No available data.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>


        {{-- Proposed Tariff Chart --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">Proposed Tariff Chart</div>
            <div class="card-body" style="padding: 15px 25px;">
                <table class="table table-bordered" id="tariffChart_tbl">
                    <thead>
                    <tr class="text-center">
                        <th width="3%" class="text-center">SL No.</th>
                        <th width="23%">Packages Name/Plan</th>
                        <th width="22%">Internet Bandwidth Package <br> Speed (Kbps/Mbps)</th>
                        <th width="14%">Price(BDT)</th>
                        <th width="15%">Duration</th>
                        <th width="15%">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($isp_tariff_chart_list) && count($isp_tariff_chart_list) > 0)
                        @foreach($isp_tariff_chart_list as $index=>$value)
                            <tr row_id="tariffChart_{{$index+1}}">
                                <td style="text-align: center;">{{$index+1}}</td>
                                <td style="text-align: center;"> {{ $value->package_name_plan }} </td>
                                <td style="text-align: center;"> {{ $value->bandwidth_package }} </td>
                                <td style="text-align: center;"> {{ $value->price }} </td>
                                <td style="text-align: center;"> {{ $value->duration }} </td>
                                <td style="text-align: center;"> {{ $value->remarks }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No available data.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Service Profile --}}
{{--        <div class="card card-magenta border border-magenta">--}}
{{--            <div class="card-header">Service Profile</div>--}}
{{--            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="form-group row">--}}
{{--                            {!! Form::label('location_of_installation', 'Location of Installation', ['class' => 'col-md-2 ']) !!}--}}
{{--                            <div class="col-md-10">: {{ $appInfo->installation_location }}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="border border-header-box">--}}
{{--                    <span class="border-header-txt">Number of Clients/Users of Internet</span>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('no_of_individual', 'Individual', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8">: {{ $appInfo->no_of_individual }}</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('no_of_corporate', 'Corporate', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8"><span>: {{ $appInfo->no_of_corporate }}</span></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="border border-header-box">--}}
{{--                    <span class="border-header-txt">Number and list of Clients/Users of domestic point to point data connectivity</span>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('corporate_user', 'Corporate user', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8">: {{ $appInfo->corporate_user }}</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('branch_user', 'Branch User', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8"><span>: {{ $appInfo->branch_user }}</span></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group row">--}}
{{--                                {!! Form::label('personal_user', 'Personal user', ['class' => 'col-md-4 ']) !!}--}}
{{--                                <div class="col-md-8"><span>: {{ $appInfo->personal_user }}</span></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}

        {{-- Technical Profile --}}
{{--        <div class="card card-magenta border border-magenta">--}}
{{--            <div class="card-header">--}}
{{--                Technical Profile--}}
{{--            </div>--}}
{{--            @php--}}
{{--                $typesOfServices = !empty($appInfo->type_of_services) ? json_decode($appInfo->type_of_services,true) : [];--}}
{{--                $cableTypes = !empty($appInfo->cable_type) ? json_decode($appInfo->cable_type,true) : [];--}}
{{--            @endphp--}}

{{--            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12 border border-header-box">--}}
{{--                        <span class="border-header-txt">Type of Service</span>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                            <label class="form-check-label" for="fth">--}}
{{--                                <input class="form-check-input" name="service_type[]" type="checkbox"--}}
{{--                                       @if(count($typesOfServices) > 0 && in_array('fth',$typesOfServices)) checked @endif disabled--}}
{{--                                       id="fth" value="fth">--}}
{{--                                FTTH--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                            <label class="form-check-label" for="dsl">--}}
{{--                                <input class="form-check-input" name="service_type[]" type="checkbox"--}}
{{--                                       @if(count($typesOfServices) > 0 && in_array('dsl',$typesOfServices)) checked @endif disabled--}}
{{--                                       id="dsl" value="dsl">--}}
{{--                                DSL--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                            <label class="form-check-label" for="adsl">--}}
{{--                                <input class="form-check-input" name="service_type[]" type="checkbox"--}}
{{--                                       @if(count($typesOfServices) > 0 && in_array('adsl',$typesOfServices)) checked @endif disabled--}}
{{--                                       id="adsl" value="adsl">--}}
{{--                                ADSL--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                            <label class="form-check-label" for="vdsl">--}}
{{--                                <input class="form-check-input" name="service_type[]" type="checkbox"--}}
{{--                                       @if(count($typesOfServices) > 0 && in_array('vdsl',$typesOfServices)) checked @endif disabled--}}
{{--                                       id="vdsl" value="vdsl">--}}
{{--                                VDSL--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 border border-header-box">--}}
{{--                        <span class="border-header-txt">Wired</span>--}}
{{--                        <div class="form-group row">--}}
{{--                            {!! Form::label('cable_length', 'Length of laid cable (Km)', ['class' => 'col-md-2 ']) !!}--}}
{{--                            <div class="col-md-10">--}}
{{--                                <span>: {{ $appInfo->cable_length }}</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="form-check form-check-inline">--}}
{{--                                    <label class="form-check-label" for="optical_fiber">--}}
{{--                                        <input class="form-check-input" name="cable_type[]" type="checkbox"--}}
{{--                                               @if(count($cableTypes) > 0 && in_array('optical_fiber',$cableTypes)) checked @endif disabled--}}
{{--                                               id="optical_fiber" value="optical_fiber">--}}
{{--                                        Optical Fiber--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check form-check-inline">--}}
{{--                                    <label class="form-check-label" for="utp">--}}
{{--                                        <input class="form-check-input" name="cable_type[]" type="checkbox"--}}
{{--                                               @if(count($cableTypes) > 0 && in_array('utp',$cableTypes)) checked @endif disabled--}}
{{--                                               id="utp" value="utp">--}}
{{--                                        UTP--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check form-check-inline">--}}
{{--                                    <label class="form-check-label" for="stp">--}}
{{--                                        <input class="form-check-input" name="cable_type[]" type="checkbox"--}}
{{--                                               @if(count($cableTypes) > 0 && in_array('stp',$cableTypes)) checked @endif disabled--}}
{{--                                               id="stp" value="stp">--}}
{{--                                        STP--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 border border-header-box">--}}
{{--                        <span class="border-header-txt">Bandwidth</span>--}}
{{--                        <table class="table table-bordered text-center" id="bandwidth_tbl">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th width="7%" class="text-center">SL No.</th>--}}
{{--                                <th width="25%">Name of the primary IIG</th>--}}
{{--                                <th width="20%">Allocation</th>--}}
{{--                                <th width="20%">Up Stream</th>--}}
{{--                                <th width="20%">Down Stream</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @if(count($isp_license_bandwidth) > 0)--}}
{{--                                @foreach($isp_license_bandwidth as $index=>$value)--}}
{{--                                    <tr row_id="bandwidth_{{$index+1}}">--}}
{{--                                        <td>{{ $index+1 }}</td>--}}
{{--                                        <td> {{$value->name_of_primary_iig}} </td>--}}
{{--                                        <td> {{$value->allocation}} </td>--}}
{{--                                        <td> {{$value->upstream}} </td>--}}
{{--                                        <td> {{$value->downstream}} </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        {{-- Disaster Recovery Centre / Data Centre --}}
{{--        <div class="card card-magenta border border-magenta">--}}
{{--            <div class="card-header">--}}
{{--                Disaster Recovery Centre / Data Centre--}}
{{--            </div>--}}
{{--            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="form-group row">--}}
{{--                            {!! Form::label('installation_address', 'Address of Installation', ['class' => 'col-md-2 ']) !!}--}}
{{--                            <div class="col-md-10">: {{ $appInfo->installation_address }}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 border border-header-box">--}}
{{--                        <span class="border-header-txt">Connectivity</span>--}}
{{--                        <table class="table table-bordered text-center" id="connectivity_tbl">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th rowspan="2" width="8%" style="text-align: center; vertical-align: middle">SL No.</th>--}}
{{--                                <th rowspan="2" width="27%" style="text-align: center; vertical-align: middle">Connectivity Provider</th>--}}
{{--                                <th width="29%" colspan="2" style="text-align: center; vertical-align: middle">Allocation</th>--}}
{{--                                <th width="28%" colspan="2" style="text-align: center; vertical-align: middle">Frequency</th>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <th style="text-align: center; vertical-align: middle">Up Stream</th>--}}
{{--                                <th style="text-align: center; vertical-align: middle">Down Stream</th>--}}
{{--                                <th style="text-align: center; vertical-align: middle">Up</th>--}}
{{--                                <th style="text-align: center; vertical-align: middle">Down</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @if(count($isp_license_connectivity) > 0)--}}
{{--                                @foreach($isp_license_connectivity as $index=>$value)--}}
{{--                                    <tr row_id="connectivity_{{$index+1}}">--}}
{{--                                        <td>{{$index+1}}</td>--}}
{{--                                        <td>{{ $value->con_provider }}</td>--}}
{{--                                        <td>{{ $value->allocation_upstream }}</td>--}}
{{--                                        <td>{{ $value->allocation_downstream }}</td>--}}
{{--                                        <td>{{ $value->frequency_up }}</td>--}}
{{--                                        <td>{{ $value->frequency_down }}</td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

    </fieldset>

    <fieldset>
        @if($appInfo->trade_license_number)
            @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'view'])
        @endif
        {{-- Necessary attachment --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Required Documents
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div id="">
                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">
                        <thead>
                            <th style="padding: 10px;">Document Name</th>
                            <th style="padding: 10px;">File</th>
                        </thead>
                        <tbody>

                        @if(count($appDynamicDocInfo) > 0)
                            @foreach($appDynamicDocInfo as $docInfo)
                                <tr>
                                    @if($docInfo->uploaded_path)
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                        <td style="padding: 10px;" class="text-center" ><a target="_blank" href="{{url('/').'/uploads/'.$docInfo->uploaded_path}}">View</a> </td>
                                    @else
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align: center;">No Data found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
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
                               Has any Application for License of ISP been rejected before?

                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes','disabled']) }}
                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no','disabled']) }}
                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q1_text', $appInfo->declaration_q2_text, array('class' =>'form-control input ', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>

                            </li>
                            <li>
                                Has any License of ISP issued previously to the Applicant/ any Share Holder/ Partner been
                                cancelled?

                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes','disabled']) }}
                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no','disabled']) }}
                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q2_text', $appInfo->declaration_q2_text, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>

                            </li>

                            <li>
                                Do the Applicant/ any Share Holder/ Partner hold any other Operator Licenses from the
                                Commission?

                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 60px; margin-left: 5px; display: none;" id="if_declaration_q3_yes">
                                    <a class="btn btn-file" target="_blank" href="{{url('/').'/'.$appInfo->declaration_q3_doc}}"><i class="fa fa-file"></i> View Document</a>
                                </div>
                            </li>
                            <li ><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> hereby certify that <span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> have carefully read the guidelines/ terms and conditions, for the license and <span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> undertake to comply with the terms and conditions therein.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> hereby certify that <span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> are not disqualified from obtaining the license.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
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
                            <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/ fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                            <li>Application without the submission of complete documents and information will not be accepted.</li>
                            <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                            <li>Fees and charges are not refundable.</li>
                            <li>The Commission is entitled to change this from time to time if necessary.</li>
                            <li>Updated documents shall be submitted during application.</li>
                            <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                            {{--                                        <li>For New Applicant only A, B and E will be applicable.</li>--}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>


    <div class="float-left">
        <a href="{{ url('/isp-license-renew/list/'. Encryption::encodeId(2)) }}" class="btn btn-default btn-md cancel"
           value="close" name="closeBtn"
           id="save_as_draft">Close
        </a>
    </div>
    {!! Form::close() !!}

</div>

<div class="modal fade" id="shortFallModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Shortfall Reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $appInfo->shortfall_reason !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/scripts/custom.min.js') }}"></script>
<script>
    let company_type = "{{$appInfo->org_type}}";

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
    $(document).on('click', '.cancelcounterpayment', function () {
        return confirm('Are you sure?');
    });

    var reg_type_id = "{{ $appInfo->regist_type }}";
    var company_type_id = "{{ $appInfo->org_type }}";
    var industrial_category_id = "{{ $appInfo->ind_category_id }}";
    var investment_type_id = "{{ $appInfo->invest_type }}";

    var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

    loadApplicationDocs('docListDiv', key);




    @if(empty($appInfo->bulk_status))
    @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 54, 55, 56, 65, 60]))
    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    const status_id = {{$appInfo->status_id}};
    let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};
    if (status_id == 15) {
        paymentInfo = {
            paymentName: 'License Fee With One Year Annual Fee',
            LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
            withOneYearAnnualFee: 1,
            annualFeeCurrentYear: 1, // numeric year
            annualFeeYearCounting: 5
        }
    } else if (status_id == 65) {
        paymentInfo = {
            paymentName: 'Second Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 2, // numeric year
            annualFeeYearCounting: 0
        }
    } else if (status_id == 54) {
        paymentInfo = {
            paymentName: 'Third Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 3, // numeric year
            annualFeeYearCounting: 0
        }
    } else if (status_id == 55) {
        paymentInfo = {
            paymentName: 'Fourth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 4, // numeric year
            annualFeeYearCounting: 0
        }
    } else if (status_id == 56) {
        paymentInfo = {
            paymentName: 'Fifth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 5, // numeric year
            annualFeeYearCounting: 0
        }
    }  else if (status_id == 60) {
        paymentInfo = {
            paymentName: '4 Year Annual Fee or BG',
            LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 0, // numeric year
            annualFeeYearCounting: 0
        }
    }
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , unfixed_amounts, '', JSON.stringify(paymentInfo));
    @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46, 64]))
    let payOrderInfo = {};
    @if($appInfo->status_id == 46)
    let paymentInfo = {
        paymentName: 'License Fee With One Year Annual Fee',
        LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
        withOneYearAnnualFee: 1,
        annualFeeCurrentYear: 1, // numeric year
        annualFeeYearCounting: 0
    }
    payOrderInfo = @json($pay_order_info);
    @elseif($appInfo->status_id == 64)
        paymentInfo = {
        paymentName: 'Shortfall for BG payment',
        LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
        withOneYearAnnualFee: 0,
        annualFeeCurrentYear: 0, // numeric year
        annualFeeYearCounting: 0
    }
    @endif
    const currentPayment = @json($payment_info);
    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , unfixed_amounts, JSON.stringify(payOrderInfo), JSON.stringify(paymentInfo), JSON.stringify(currentPayment));
    @endif
    @endif

{{--    @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 25, 54, 55, 56]))--}}
{{--    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;--}}
{{--    const status_id = {{$appInfo->status_id}};--}}
{{--    let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};--}}
{{--    if (status_id == 15) {--}}
{{--        paymentInfo = {--}}
{{--            paymentName: 'License Fee With One Year Annual Fee',--}}
{{--            LicenseOrAnnualFee: 1, // license fee=1; annual fee=2--}}
{{--            withOneYearAnnualFee: 1,--}}
{{--            annualFeeCurrentYear: 1, // numeric year--}}
{{--            annualFeeYearCounting: 5--}}
{{--        }--}}
{{--    } else if (status_id == 25) {--}}
{{--        paymentInfo = {--}}
{{--            paymentName: 'Second Year Annual Fee',--}}
{{--            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2--}}
{{--            withOneYearAnnualFee: 0,--}}
{{--            annualFeeCurrentYear: 2, // numeric year--}}
{{--            annualFeeYearCounting: 0--}}
{{--        }--}}
{{--    } else if (status_id == 54) {--}}
{{--        paymentInfo = {--}}
{{--            paymentName: 'Third Year Annual Fee',--}}
{{--            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2--}}
{{--            withOneYearAnnualFee: 0,--}}
{{--            annualFeeCurrentYear: 3, // numeric year--}}
{{--            annualFeeYearCounting: 0--}}
{{--        }--}}
{{--    } else if (status_id == 55) {--}}
{{--        paymentInfo = {--}}
{{--            paymentName: 'Fourth Year Annual Fee',--}}
{{--            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2--}}
{{--            withOneYearAnnualFee: 0,--}}
{{--            annualFeeCurrentYear: 4, // numeric year--}}
{{--            annualFeeYearCounting: 0--}}
{{--        }--}}
{{--    } else if (status_id == 56) {--}}
{{--        paymentInfo = {--}}
{{--            paymentName: 'Fifth Year Annual Fee',--}}
{{--            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2--}}
{{--            withOneYearAnnualFee: 0,--}}
{{--            annualFeeCurrentYear: 5, // numeric year--}}
{{--            annualFeeYearCounting: 0--}}
{{--        }--}}
{{--    }--}}
{{--    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',--}}
{{--        'paymentPanel',--}}
{{--        "{{ CommonFunction::getUserFullName() }}",--}}
{{--        "{{ Auth::user()->user_email }}",--}}
{{--        "{{ Auth::user()->user_mobile }}",--}}
{{--        "{{ Auth::user()->contact_address }}"--}}
{{--        , unfixed_amounts, '', JSON.stringify(paymentInfo));--}}
{{--    @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46]))--}}
{{--    const payOrderInfo = @json($pay_order_info);--}}
{{--    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;--}}
{{--    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',--}}
{{--        'paymentPanel',--}}
{{--        "{{ CommonFunction::getUserFullName() }}",--}}
{{--        "{{ Auth::user()->user_email }}",--}}
{{--        "{{ Auth::user()->user_mobile }}",--}}
{{--        "{{ Auth::user()->contact_address }}"--}}
{{--        , unfixed_amounts, JSON.stringify(payOrderInfo));--}}
{{--    @endif--}}
</script>

<script>
    $(document).ready(function () {

        var type = "{{$appInfo->isp_license_type}}";
        if (type == 1) {
            $('#division').css('display', 'none');
            $('#district').css('display', 'none');
            $('#thana').css('display', 'none');
        }
        if (type == 2) {
            $('#division').css('display', 'inline');
            $('#district').css('display', 'none');
            $('#thana').css('display', 'none');
        }

        if (type == 3) {
            $('#division').css('display', 'inline');
            $('#district').css('display', 'inline');
            $('#thana').css('display', 'none');
        }

        if (type == 4) {
            $('#division').css('display', 'inline');
            $('#district').css('display', 'inline');
            $('#thana').css('display', 'inline');
        }


        var declaration_q1 = "{{$appInfo->declaration_q1}}";
        var declaration_q2 = "{{$appInfo->declaration_q2}}";
        var declaration_q3 = "{{$appInfo->declaration_q3}}";

       if(declaration_q1 == 'Yes') {
            $('#if_declaration_q1_yes').css('display','inline');
       }

        if(declaration_q2 == 'Yes') {
            $('#if_declaration_q2_yes').css('display','inline');
        }

        if(declaration_q3 == 'Yes') {
            $('#if_declaration_q3_yes').css('display','inline');
        }


    });
</script>

<script>
    setTimeout(function() {
        let annualPayment = document.getElementById('annual_fee');
        if(annualPayment.checked){
            let payOrderPayment = document.getElementById('pay_order_payment');
            if(payOrderPayment.checked){
                const bgPaymentFields = document.getElementsByClassName('bg_payment_fields');
                for(const elem of bgPaymentFields) {
                    elem.style.display = 'none';
                    for(const inputField of elem.querySelectorAll("input, select")) {
                        inputField.classList.remove('required');
                        inputField.classList.remove('require_check');
                        inputField.classList.remove('error');
                        inputField.required = false;
                    }
                }
            }
        }
    },8000);
</script>
