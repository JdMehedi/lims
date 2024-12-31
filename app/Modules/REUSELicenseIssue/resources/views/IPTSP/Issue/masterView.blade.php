<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/stylesheets/custom.min.css') }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.css") }}"/>
<div id="paymentPanel"></div>
<div class="card" style="border-radius: 10px;" id="applicationForm">
    {!! Form::open([
        'url' => url('iptsp-license-issue/store'),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
        'onSubmit' => 'enablePath()',
    ]) !!}
    @csrf

    <fieldset>
        @if(in_array($appInfo->status_id, [2,5,25, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78]))
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Related Reports
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    @if(isset($appInfo->dd_file_1))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation
                            Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_2))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission
                            Meeting Agenda</a>
                    @endif
                    @if(isset($appInfo->dd_file_3))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry
                            Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_4))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_4)}}">View Inspection
                            Report</a>
                    @endif
                    @if(isset($appInfo->shortfall_reason))
                        <a class="btn btn-primary m-1" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
                    @endif
                    @if(isset($latter[1]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
                    @endif
                    @if(isset($latter[2]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
                    @endif
                    @if(isset($latter[3]))
                    <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[3])}}">BPO/Call Center Registration Certificate</a>
                    @endif
                    @if(isset($latter[4]))
                    <a class="btn btn-primary m-1
                    " target="_blank" href="{{url($latter[4])}}">BG Payment Letter</a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Company/Organization Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view', 'extra' => ['address2']])

        {{--applicant profile--}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view', 'extra' => ['address2']])

        {{--         Contact Person--}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view', 'extra' => ['address2']])

        {{--            Name of Authorized Signatory--}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Name of Authorized Signatory
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->cs_person_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->cs_designation }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->cs_mobile }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: {{$appInfo->cs_person_email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('district', 'District', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: @if(!empty($signatory_district)){{ $signatory_district}} @endif</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: @if(!empty($signatory_upazila)){{$signatory_upazila }} @endif</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->cs_person_address }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row ">
                            {!! Form::label('Photograph', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <label class="center-block image-upload"
                                       for="correspondent_photo_1">
                                    <figure>
                                        <img style="height: 99px; width: 95px; border: 1px solid #EBEBEB;"
                                             src="{{$appInfo->cs_photo_base64 !=""? asset($appInfo->cs_photo_base64):asset('assets/images/demo-user.jpg') }}"
                                             class="img-responsive img-thumbnail"/>
                                    </figure>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($appInfo->bulk_status != '1')
        {{--        Details of Existing ISP License--}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Details of Existing ISP License
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('isp_license_number', 'ISP License Number', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->isp_li_number }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('isp_license_date_of_expire', 'ISP License Date of Expire', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->isp_li_date_expire }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('multi_license ', 'Other License Awarded by the commision to the License ', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->multi_license }}</span>
                            </div>
                        </div>
                        @isset($appInfo->prev_license_copy)
                        <div class="form-group row">
                            {!! Form::label('multi_license ', 'Existing License Documents', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span><a class="btn btn-info" href="{{ asset($appInfo->prev_license_copy) }}" target="_blank">Open file</a></span>
                            </div>
                        </div>
                        @endisset
                    </div>

                    <div class="col-md-6"  >
                        <div class="form-group row">
                            {!! Form::label('types_of_isp_license', 'Types of ISP License', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                @if($appInfo->isp_license_types == 1)
                                    <span>: Nationwide</span>
                                @elseif($appInfo->isp_license_types==2)
                                    <span>: Divisional  </span>
                                @elseif($appInfo->isp_license_types==3)
                                    <span>:District</span>
                                @elseif($appInfo->isp_license_types==4)
                                    <span>: Thana/Upazila</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('isp_license_issue_date', 'ISP License Issue Date', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->isp_license_issue_date }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="district" style="display: none;"></div>
                    <div class="col-md-6" id="thana" style="display: none;">
                        <div class="form-group row" >
                            {!! Form::label('applicant_upazila_thana', 'Thana', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8 {{ $errors->has('applicant_upazila_thana') ? 'has-error' : '' }}">
                                {!! Form::select('applicant_upazila_thana',[''=>'Select',],'', ['class' => 'form-control', 'id' => 'applicant_upazila_thana']) !!}
                                {!! $errors->first('applicant_upazila_thana', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{--  Shareholder/partner/proprietor Details--}}
        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])
        @if($appInfo->bulk_status != '1')
        {{--        Investment Information--}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Investment Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('local_investment', 'Local Investment', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->local_investment }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('present_value_of_total_investment', 'Present Value of Total Investment', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->pre_val_t_invest }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('total_investment', 'Total Investment', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->total_investment }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6"  >
                        <div class="form-group row">
                            {!! Form::label('foreign_investment', 'Foreign Investment', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->foreign_investment }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('gross_revenue_earned_in_last_year', 'Gross Revenue Earned in Last Year', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->gr_rev_last_year }}</span>
                            </div>
                        </div>
                        @isset($appInfo->gr_rev_last_year_img)
                            <div class="form-group row">
                                {!! Form::label('gross_revenue_earned_in_last_year_file', 'Gross Revenue Earned in Last Year file', ['class' => 'col-md-5']) !!}
                                <div class="col-md-7">
                                    <a href="{{ asset($appInfo->gr_rev_last_year_img) }}" target="_blank">: Open file</a>
                                </div>
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        {{--         Employee Information--}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Employee Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('employee_information', 'Employee Information', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->emp_info }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"  >
                        <div class="form-group row">
                            {!! Form::label('total_it_specialist', 'Total IT Specialist', ['class' => 'col-md-5']) !!}
                            <div class="col-md-7">
                                <span>: {{ $appInfo->tit_specialist }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Types of ISP License --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Types of IPTSP License
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('type_of_isp_licensese', 'Types of IPTSP License', ['class' => 'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                @if($appInfo->isptspli_type==1)
                                    <span>: Nationwide </span>
                                @elseif($appInfo->isptspli_type==2)
                                    <span>:Divisional </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(isset($license_type_division))
                        <div class="col-md-6" id="division" >
                            <div class="form-group row">
                                {!! Form::label('isp_licensese_area_division', 'Divisional', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $license_type_division }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{--Coverage Area Information--}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Coverage Area Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('coverage_area', 'Coverage Area', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                @if($appInfo->cover_area==1)
                                    <span>:Nationwide</span>
                                @elseif($appInfo->cover_area==2)
                                    <span>:Divisional</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('total_coverage_area', 'Total Coverage Area ', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->t_cover_area }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('coverage_district', 'District', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->cover_dis }}</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{--Coverage Out of Area In Rural Area Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Coverage Out of Area In Rural Area Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('coverage_out_of_area', 'Coverage Area', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                @if($appInfo->cover_ot_area==1)
                                    <span>:Nationwide</span>
                                @elseif($appInfo->cover_ot_area==2)
                                    <span>:Divisional</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('total_coverage_out_of_area', 'Total Coverage Area ', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ isset($appInfo->tc_out_area) ? $appInfo->tc_out_area: '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('coverage_out_of_district', 'District', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ isset($appInfo->cover_ot_dis) ? $appInfo->cover_ot_dis: '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--Date of Commencement of the Service  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Date of Commencement of the Service
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row" style="margin-top:10px;">
                            {!! Form::label('commencement_date', 'Date', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ isset($appInfo->commencement_date) ? $appInfo->commencement_date: '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >

                    </div>
                </div>
            </div>
        </div>

        {{--Information of Existing Subscriber Level  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Information of Existing Subscriber Level
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('existing_subscriber_dial_up', 'Dial-up', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->existsubs_dial_up }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('existing_subscriber_corporate', 'Corporate', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->existsubs_corporate }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('existing_subscriber_individual', 'Individual', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->existsubs_individual }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('existing_subscriber_broadband', 'Broadband', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->existsubs_broadband }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('existing_subscriber_name_with_corporate_clients_subscriber_number', 'Name of Corporate Clients with Subscriber Number', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->exsubs_corpc_namenum }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wired Network Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Wired Network Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('wired_network_length_of_laid_cable', 'Dial-up', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wirenet_len_lid_cabl }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('wired_network_optical_fiber', 'Wired network optical fiber', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wir_net_optic_fiber }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('wired_network_dsl', 'DSL', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wire_net_dsl }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('wired_network_adsl', 'ADSL', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wire_net_adsl }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('wired_network_utp', 'UTP', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wire_net_utp }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('wired_network_stp', 'STP', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wire_net_stp }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('wired_network_other', 'Other', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wire_net_other }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--Bandwidth Details for Last Years Level  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Bandwidth Details for Last Years Level
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('bandwidth_lastyear_total_allocation', 'Total Allocation', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bnd_lst_totl_alloc }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('bandwidth_lastyear_total_utilization', 'Total utilization', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bnd_lst_totl_util }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('bandwidth_lastyear_iig', 'IIG', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bnd_lst_iig }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('bandwidth_lastyear_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bnd_lst_iplc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('bandwidth_lastyear_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bnd_lst_vsat }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>

                {{--                 Uplink Information--}}
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Uplink Information</legend>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_uplink_iig', 'IIG', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_upli_iig }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_uplink_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_upli_iplc }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_uplink_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_upli_vsat }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </fieldset>
                <br>
                {{--                 Medium for Uplink Allocation--}}
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Medium for Uplink Allocation</legend>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_uplink_iig', 'IIG', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_upli_iig }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_uplink_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_upli_iplc }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_uplink_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_upli_vsat }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </fieldset>

                {{--                 Downlink Allocation Information--}}
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Downlink Allocation Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_downlink_iig', 'IIG', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_downli_iig }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_downlink_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_downli_iplc }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_downlink_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_downli_vsat }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </fieldset>
                <br>
                {{--                 Medium Downlink Allocation Information--}}
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Medium Downlink Allocation Information</legend>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_downlink_iig', 'IIG', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_downli_iig }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_downlink_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_downli_iplc }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_medium_downlink_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_med_downli_vsat }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </fieldset>
                {{--                 Provider Information--}}
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Provider Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_provider_name', 'Provider Name', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_prov_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_provider_iig', 'IIG', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_prov_iig }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_provider_iplc', 'IPLC', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_prov_iplc }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('bandwidth_lastyear_provider_vsat', 'VSAT', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->bnd_lst_prov_vsat }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- Connected by the ISP Information --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Connected by the ISP Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <table class="table table-bordered" id="IPTSPlistOfISPinformation_tbl">
                    <thead>
                    <tr>
                        <th width="7%" class="text-center">SL</th>
                        <th width="45%">Institution Type</th>
                        <th width="40%">Institution Name</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $instituteType = [1=>"School",2=>"College",3=>"University"];
                    ?>
                    @foreach($iptsp_connected_isp_info as $key=>$item)
                        <tr row_id="IPTSPlistOfISPinformation_1">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$instituteType[$item->institution_type]}}</td>
                            <td>{{$item->institution_name}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Average Minimam Growth Rate of Subscribers for per year Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Average Minimam Growth Rate of Subscribers for per year Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('subscriber_individual', 'Individual ', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->subsc_indivi }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('subscriber_corporate', 'Corporate', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->subsc_corpo }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- POP Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                POP Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('no_of_POP', 'No. of POP ', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->no_of_POP }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('location', 'Location', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wireless Network Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Wireless Network Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('wireless_number_of_bis_pop', 'Number of BIS/POP', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wirls_num_bis_pop }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('wireless_frequency', 'Frequency', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->wireless_frequency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Backup Information  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Backup Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_number_of_vsat', 'Number of VSAT (If Applicable)', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_num_of_vsat }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_uplink_allocation', 'Uplink Allocation', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_uplin_alloc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_downlink_allocation', 'Downlink Allocation', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_downlin_alloc }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_uplink_frequency', 'Uplink Frequency', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_uplin_freq }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_downlink_frequency', 'Downlink Frequency', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_dwnlin_freq }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            {!! Form::label('backup_info_of_description', 'Description', ['class' => 'col-md-2']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->bkupinf_desc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Per Subscriber Average width  --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Per Subscriber Average width
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('per_subscriber_individual', 'Individual', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->per_subsc_indivi }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="form-group row">
                            {!! Form::label('per_subscriber_corporate', 'Corporate', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
                                <span>: {{ $appInfo->per_subsc_corpo }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--    </fieldset>--}}
        @endif

        <fieldset>
            @includeIf('common.subviews.RequiredDocuments', ['mode' => 'view'])
            {{--Declaration--}}
            <div class="card card-magenta border border-magenta mt-4">
                <div class="card-header">
                    Declaration
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-12">
                            <ol>
                                <li>
                                    <label class="required-star !font-normal">
                                        Has any Application for License of the Applicant any Share Holder/Partner IPTSP been rejected before?
                                    </label>

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q1', 'Yes', $appInfo->dclar_q1 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes','disabled']) }}
                                        {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q1', 'No', $appInfo->dclar_q1 == 'No' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no','disabled']) }}
                                        {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <div class="row" id="declaration_q1_date_row" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="row form-group">
                                                    {!! Form::label('declaration_q1_date', 'Date of Application', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-6{{ $errors->has('declaration_q1_date') ? 'has-error' : '' }}">
                                                        <span>: {{ isset( $appInfo->dclar_q1_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->dclar_q1_date): '' }}</span>
                                                        {{--                                                    {!! Form::date('declaration_q1_date', isset($appInfo->dclar_q1_date) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->dclar_q1_date): '' , ['class' => 'form-control', 'placeholder' => '','readonly']) !!}--}}
                                                        {{--                                                    {!! $errors->first('declaration_q1_date', '<span class="help-block">:message</span>') !!}--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="if_declaration_q1_yes" style="display: none;">
                                            <div class="col-md-12">
                                                <div class="row form-group">
                                                    <span>  {{ $appInfo->dclar_q1_text ?? '' }}</span>
                                                    {{--                                                {{ Form::textarea('declaration_q1_text', $appInfo->dclar_q1_text, array('class' =>'form-control input required', 'placeholder'=>'Please give details reasons for rejection', 'rows' =>3, '' => '' ,'required','readonly'))}}--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <label class="required-star !font-normal">
                                        Do the Applicant /Share Holder Partner hold any other Operator Licenses from the Commission?
                                    </label>

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q2', 'Yes', $appInfo->dclar_q2 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes','disabled']) }}
                                        {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q2', 'No', $appInfo->dclar_q2 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no','disabled']) }}
                                        {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    {{--                                {{dd($appInfo->dclar_q2_serv_lst)}}--}}
                                    <div style="margin-top: 20px;" >
                                        <div id="if_declaration_q2_yes" style="display: none;" >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q2_service_list', 'Service List', ['class' => 'col-md-4']) !!}
                                                        {{--                                                    <div class="col-md-8{{ $errors->has('declaration_q2_service_list') ? 'has-error' : '' }}">--}}
                                                        {{--                                                        {!! Form::select('declaration_q2_service_list',[''=>'Select', '1'=>'ISP','2'=>'NIX'], $appInfo->dclar_q2_serv_lst, ['class' => 'form-control', 'placeholder' => '']) !!}--}}
                                                        {{--                                                        {!! $errors->first('declaration_q2_service_list', '<span class="help-block">:message</span>') !!}--}}
                                                        {{--</div>--}}
                                                        <div class="col-md-8">
                                                            <span>: {{ $appInfo->dclar_q2_serv_lst}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q2_license_number', 'License Number', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8">
                                                            <span>: {{ $appInfo->dclar_q2_licen_num }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" >
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q2_company_name', 'Company Name', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8">
                                                            <span>: {{ $appInfo->dclar_q2_comp_name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q2_share_holder_name', 'Share Holder Name', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8">
                                                            <span>: {{ $appInfo->dclar_q2_shar_holdr_name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <label class="required-star !font-normal">
                                        Has any other License of the Applicant/any Share Holder/Partner been rejected before?
                                    </label>

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q3', 'Yes',$appInfo->dclar_q3 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                        {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q3', 'No', $appInfo->dclar_q3 == 'No' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                        {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <div id="if_declaration_q3_yes" style="display: none;">
                                            <div class="row" >
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q3_date', 'Date of Application', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-6">
                                                            <span>: {{ isset( $appInfo->dclar_q3_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->dclar_q3_date): '' }}</span>
                                                            {{--                                                        {!! Form::text('declaration_q3_date', isset( $appInfo->dclar_q3_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->dclar_q3_date): '', ['class' => 'form-control', 'placeholder' => '','readonly']) !!}--}}
                                                            {{--                                                        {!! $errors->first('declaration_q3_date', '<span class="help-block">:message</span>') !!}--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row form-group">
                                                        <span>  {{ isset( $appInfo->dclar_q3_text) ?  $appInfo->dclar_q3_text: '' }}</span>
                                                        {{--                                                    {{ Form::textarea('declaration_q3_text',isset( $appInfo->dclar_q3_text) ?  $appInfo->dclar_q3_text: ''  , array('class' =>'form-control input', 'placeholder'=>'Please give details reasons for rejection',  'rows' =>3, '' => '' ,'required','disabled'))}}--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <label class="required-star !font-normal">
                                        Do the Applicant/ its owner(s)/ any of its director(s)/ partner(s) were involved in any illegal call termination using VoIP technology?
                                    </label>

                                    <div style="margin-top: 20px;">
                                        {{ Form::radio('declaration_q4', 'Yes', $appInfo->dclar_q4 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes','disabled']) }}
                                        {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                        {{ Form::radio('declaration_q4', 'No', $appInfo->dclar_q4 == 'No' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no','disabled']) }}
                                        {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <div id="if_declaration_q4_yes" style="display: none;">
                                            <div class="row" >
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q4_illegal_VoIP_activities', 'Period of Involvement in illegal VoIP activities', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8{{ $errors->has('declaration_q4_illegal_VoIP_activities') ? 'has-error' : '' }}">
                                                            {!! Form::text('declaration_q4_illegal_VoIP_activities', isset( $appInfo->dclar_q4_iligl_VoIP_activiti) ?  $appInfo->dclar_q4_iligl_VoIP_activiti: '', ['class' => 'form-control', 'placeholder' => '','disabled']) !!}
                                                            {!! $errors->first('declaration_q4_illegal_VoIP_activities', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('declaration_q4_case_no', 'Case No', ['class' => 'col-md-4']) !!}
                                                        <div class="col-md-8{{ $errors->has('declaration_q4_case_no') ? 'has-error' : '' }}">
                                                            {!! Form::text('declaration_q4_case_no', isset($appInfo->dclar_q4_case_no) ? $appInfo->dclar_q4_case_no: '' , ['class' => 'form-control', 'placeholder' => '','disabled']) !!}
                                                            {!! $errors->first('declaration_q4_case_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Administrative fine paid to the Commission</legend>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row form-group">
                                                            {!! Form::label('declaration_q4_amount', 'Amount', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8{{ $errors->has('declaration_q4_amount') ? 'has-error' : '' }}">
                                                                {!! Form::text('declaration_q4_amount', $appInfo->dclar_q4_amount, ['class' => 'form-control', 'placeholder' => '','disabled']) !!}
                                                                {!! $errors->first('declaration_q4_amount', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row form-group">
                                                            {!! Form::label('declaration_q4_cheque_or_bank_draft_no', 'Cheque No./ Bank Draft No', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8{{ $errors->has('declaration_q4_cheque_or_bank_draft_no') ? 'has-error' : '' }}">
                                                                {!! Form::text('declaration_q4_cheque_or_bank_draft_no', $appInfo->dclar_q4_chq_bank_drft, ['class' => 'form-control', 'placeholder' => '','disabled']) !!}
                                                                {!! $errors->first('declaration_q4_cheque_or_bank_draft_no', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="row" >
                                                <div class="col-md-6">
                                                    <div class="row form-group">
                                                        {!! Form::label('', 'Undertaking given to the Commission', ['class' => 'col-md-8']) !!}
                                                        <div class="col-md-4">
                                                            {{ Form::radio('declaration_q4_given_commision', 'Yes', $appInfo->dclar_q4_givn_commision == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_given_commision_yes','disabled']) }}
                                                            {{ Form::label('declaration_q4_given_commision_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                            {{ Form::radio('declaration_q4_given_commision', 'No', $appInfo->dclar_q4_givn_commision == 'No' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_given_commision_no','disabled']) }}
                                                            {{ Form::label('declaration_q4_given_commision_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>

                                <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>



        </fieldset>

        <div class="float-left">
            <a href="{{ url('client/iptsp-license-issue/list/'. Encryption::encodeId(21)) }}" class="btn btn-default btn-md cancel"
               value="close" name="closeBtn"
               id="save_as_draft">Close
            </a>
        </div>
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
    @if($appInfo->dclar_q1 == 'Yes')
    $('#if_declaration_q1_yes').css('display', 'block');
    $('#declaration_q1_date_row').css('display', 'block');
    @endif


    @if($appInfo->dclar_q2 == 'Yes')
    $('#if_declaration_q2_yes').css('display', 'block');
    @endif

    @if($appInfo->dclar_q3 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif
    @if($appInfo->dclar_q4 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif
    $("#if_declaration_q1_yes").change(function () {
        alert($(this).is(":checked"));
    });

    $("#declaration_q1_no").on('click', function () {
        $('#if_declaration_q1_yes').css('display', 'none');
    });

    $("#declaration_q2_yes").on('click', function () {
        $('#if_declaration_q2_yes').css('display', 'inline');
    });
    $("#declaration_q2_no").on('click', function () {
        $('#if_declaration_q2_yes').css('display', 'none');
    });

    $("#declaration_q3_yes").on('click', function () {
        $('#if_declaration_q3_yes').css('display', 'inline');
    });
    $("#declaration_q3_no").on('click', function () {
        $('#if_declaration_q3_yes').css('display', 'none');
    });

    //declaration compnay type
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

    // display payment panel
    @if(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 25, 54, 55, 56, 57, 69, 70, 71, 72, 73, 74, 75, 76, 77]))
    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    const status_id = {{$appInfo->status_id}};
    let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};
    if (status_id == 15) {
        paymentInfo = {
            paymentName: 'License Fee With One Year Annual Fee',
            LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
            withOneYearAnnualFee: 1,
            annualFeeCurrentYear: 1, // numeric year
            annualFeeYearCounting: 15
        }
    } else if (status_id == 25) {
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
    }else if (status_id == 57) {
        paymentInfo = {
            paymentName: 'Sixth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 6, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 69) {
        paymentInfo = {
            paymentName: 'Seventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 7, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 70) {
        paymentInfo = {
            paymentName: 'Eighth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 8, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 71) {
        paymentInfo = {
            paymentName: 'Nineth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 9, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 72) {
        paymentInfo = {
            paymentName: 'Tenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 10, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 73) {
        paymentInfo = {
            paymentName: 'Eleventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 11, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 74) {
        paymentInfo = {
            paymentName: 'Twelveth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 12, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 75) {
        paymentInfo = {
            paymentName: 'Thirteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 13, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 76) {
        paymentInfo = {
            paymentName: 'Fourteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 14, // numeric year
            annualFeeYearCounting: 0
        }
    }else if (status_id == 77) {
        paymentInfo = {
            paymentName: 'Fifteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 15, // numeric year
            annualFeeYearCounting: 0
        }
    }
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        unfixed_amounts, '', JSON.stringify(paymentInfo));
    @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46]))
    const paymentInfo = {
        paymentName: 'License Fee With One Year Annual Fee',
        LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
        withOneYearAnnualFee: 1,
        annualFeeCurrentYear: 1, // numeric year
        annualFeeYearCounting: 0
    }
    const payOrderInfo = @json($pay_order_info);
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
</script>
