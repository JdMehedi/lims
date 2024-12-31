<link rel="stylesheet" href="{{ asset("assets/plugins/bootstrap-3.4.1-dist/css/bootstrap.css") }}">
<style>

    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }


    @media (min-width: 767px) {
        .addressField{
            width: 79.5%;
            float: right
        }
    }
    @media (max-width: 480px) {
        .section_head{
            font-size: 26px;
            font-weight: 400;
            margin-top: 5px;
        }
        label {
            font-weight: normal;
            font-size: 14px;
        }
        span{
            font-size: 14px;
        }
        .panel-body{
            padding: 10px 0 !important;
        }
        .form-group{
           margin: 0;
        }
        .image_mobile{
            width: 100%;
        }
    }
</style>

    {{-- Start if this is applicant user and status is 15, 32 (proceed for payment) --}}
    @if(in_array(Auth::user()->user_type,['5x505']) && in_array($appInfo->status_id, [15, 32]))
        @include('SonaliPayment::government-payment-information')
    @endif
    {{-- End if this is applicant user and status is 15, 32 (proceed for payment) --}}

    <div class="card" style="border-radius: 10px;" id="inputForm">
        <div style="padding: 10px 15px">
            <div class="float-left">
                <span class="section_head">{!!trans('IndustryReRegistration::messages.ind_re_reg')!!}</span>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="card-body" style="padding: 0 15px">

            {{--Re-reg info--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    {!!trans('IndustryReRegistration::messages.re_reg_info')!!}
                </div>
                <div class="card-body" style="padding: 15px 10px;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->org_nm_bn }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('company_name_english', trans('IndustryReRegistration::messages.manual_service_name'),
                                                               ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->service_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('project_name', trans('IndustryReRegistration::messages.manual_reg_number'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{$appInfo->manual_reg_number}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_type_id', trans('IndustryReRegistration::messages.manual_reg_date'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: <span class="input_ban">{{date('d-m-Y', strtotime($appInfo->manual_reg_date))}}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_name_bn', trans('IndustryReRegistration::messages.reg_issued_office'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->reg_office_name_bn }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('total_investment', trans('CompanyProfile::messages.total_investment'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->total_investment }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--Company ceo info--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    {!!trans('CompanyProfile::messages.company_ceo')!!}
                </div>
                <div class="card-body" style="padding: 15px 10px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_director_type', trans('CompanyProfile::messages.select_directors'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->director_type }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_name', trans('CompanyProfile::messages.name'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->ceo_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_fatherName', trans('CompanyProfile::messages.father_name'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->ceo_father_nm }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_nationality', trans('CompanyProfile::messages.nationality'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->ceo_nationality }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                @if($appInfo->nid != null)
                                    {!! Form::label('company_ceo_nid', trans('CompanyProfile::messages.nid'),
                                ['class'=>'col-md-5 col-xs-5']) !!}
                                    <div class="col-md-7 col-xs-7">
                                        <span>:</span><span class="input_ban"> {{ $appInfo->nid }}</span>
                                    </div>
                                @else
                                    {!! Form::label('passport', trans('CompanyProfile::messages.passport_no'),
                               ['class'=>'col-md-5 col-xs-5']) !!}
                                    <div class="col-md-7 col-xs-7">
                                        <span>:</span><span class=""> {{ $appInfo->passport }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_dob',trans('CompanyProfile::messages.dob'),['class'=>'col-md-5 col-xs-5']) !!}
                                <div class=" col-md-7 col-xs-7">
                                    <span>:</span><span class="input_ban"> {{ date('d-m-Y', strtotime($appInfo->dob)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_designation_id', trans('CompanyProfile::messages.designation'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->designation }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_email', trans('CompanyProfile::messages.email'),
                                                               ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->ceo_email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('company_ceo_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>:</span><span class="input_ban"> {{ $appInfo->ceo_mobile }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('ceo_name', trans('CompanyProfile::messages.signature'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span> <img class="image_mobile" src="{{ '/'.$appInfo->entrepreneur_signature }}" alt="Image not found!"> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="card-header section_head">{!!trans('CompanyProfile::messages.reg_information')!!}</p>
            {{--Investment--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">চ.
                    {!! trans('CompanyProfile::messages.investment') !!}
                </div>
                <div class="card">
                    <div style="background-color: #F7F7F7; border: 1px solid #DDDDDD; border-bottom: none;">
                        <h4 class="card-header" class="text-center"
                            style="font-size: 16px; margin-top: 5px; font-weight: bold">{!! trans('CompanyProfile::messages.country_wise_loan_source') !!}</h4>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <table id="loanSourceTable" class="table table-bordered dt-responsive" cellspacing="0"
                               width="100%">
                            <thead class="text-center section_heading1">
                            <tr style="font-size: 16px;">
                                <th style="font-weight: normal;" class="text-center">{!! trans('CompanyProfile::messages.country_name') !!}</th>
                                <th style="font-weight: normal;" class="text-center">{!! trans('CompanyProfile::messages.org_name') !!}</th>
                                <th style="font-weight: normal;" class="text-center">{!! trans('CompanyProfile::messages.loan_amount') !!}</th>
                                <th style="font-weight: normal;" class="text-center">{!! trans('CompanyProfile::messages.loan_taking_date') !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($loanSrcCountry as $item)
                                <tr class="text-center" id="loanSourceRow" data-number="1">
                                    <td>
                                        {{ $item->country_name }}
                                    </td>
                                    <td>
                                        {{ $item->loan_org_nm }}
                                    </td>
                                    <td class="input_ban">
                                        {{ $item->loan_amount }}
                                    </td>
                                    <td class="input_ban">
                                        {{ date('d-m-Y', strtotime($item->loan_receive_date)) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{--Raw material details--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    {!!trans('CompanyProfile::messages.raw_material_package_details')!!}
                </div>
                <div class="card-body" style="padding: 15px 25px;">

                    {{--Ind local material--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            ক. {!!trans('CompanyProfile::messages.locally_collected')!!}
                        </div>
                        <div class="card-body" style="padding: 15px 10px;">
                            <table class="table table-bordered">
                                <tr class="section_heading1">
                                    <th class="text-center">{!!trans('CompanyProfile::messages.raw_material_package_name')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.amount')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.amount_unit')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.price_lak_taka')!!}</th>
                                </tr>
                                <tbody>
                                @foreach($localRawMaterial as $item)
                                    <tr class="text-center">
                                        <td>{{$item->local_raw_material_name}}</td>
                                        <td class="input_ban">{{$item->local_raw_material_quantity}}</td>
                                        <td class="input_ban">{{$item->name_bn}}</td>
                                        <td class="input_ban">{{$item->local_raw_material_amount_bdt}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center">
                                        {!!trans('CompanyProfile::messages.grand_total')!!}
                                    </td>
                                    <td class="text-center input_ban">
                                        {{$appInfo->local_raw_price_total}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--Ind imported material--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            খ. {!!trans('CompanyProfile::messages.imported')!!}
                        </div>
                        <div class="card-body" style="padding: 15px 10px;">
                            <table class="table table-bordered">
                                <tr class="section_heading1">
                                    <th class="text-center">{!!trans('CompanyProfile::messages.raw_material_package_name')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.amount')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.amount_unit')!!}</th>
                                    <th class="text-center">{!!trans('CompanyProfile::messages.price_lak_taka')!!}</th>
                                </tr>
                                <tbody>
                                @foreach($importedRawMaterial as $item)
                                    <tr class="text-center">
                                        <td>{{$item->imported_raw_material_name}}</td>
                                        <td class="input_ban">{{$item->imported_raw_material_quantity}}</td>
                                        <td class="input_ban">{{$item->name_bn}}</td>
                                        <td class="input_ban">{{$item->imported_raw_material_amount_bdt}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center">
                                        {!!trans('CompanyProfile::messages.grand_total')!!}
                                    </td>
                                    <td class="text-center input_ban">
                                        {{$appInfo->imported_raw_price_total}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <p class="section_head">{!!trans('CompanyProfile::messages.attachments')!!}</p>
            {{--Applicant info--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    {!!trans('CompanyProfile::messages.approved_applicant_info')!!}
                </div>

                <div class="card-body" style="padding: 15px 25px;">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('auth_person_nm', trans('CompanyProfile::messages.name'),
                                                               ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->auth_person_nm  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('auth_person_desig', trans('CompanyProfile::messages.designation'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->auth_person_desig  }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('auth_person_address', trans('CompanyProfile::messages.address'),
                                                                ['class'=>'col-md-2 col-xs-5']) !!}
                                <div class="col-md-10 col-xs-7 addressField"
                                >
                                    <span>: {{ $appInfo->auth_person_address  }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('auth_person_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>:</span><span class="input_ban"> {{ $appInfo->auth_person_mobile  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('auth_person_email', trans('CompanyProfile::messages.email'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span>: {{ $appInfo->auth_person_email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('authorization_letter', trans('CompanyProfile::messages.approved_permission_letter'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    @isset( $appInfo->authorization_letter )
                                        <a href="{{ '/uploads/'.$appInfo->authorization_letter }}" target="_blank" class="btn btn-danger btn-sm">Open</a>
                                    @endisset
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group row">
                                {!! Form::label('correspondent_photo', trans('CompanyProfile::messages.image'),
                                                                ['class'=>'col-md-5 col-xs-5']) !!}
                                <div class="col-md-7 col-xs-7">
                                    <span><img src="{{ '/'.$appInfo->auth_person_pic  }}" alt="No image" width="75px"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--Necessary attachment--}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    {!!trans('CompanyProfile::messages.necessary_attachment')!!}
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    <input type="hidden" id="doc_type_key" name="doc_type_key">
                    <div id="docListDiv"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset("assets/scripts/custom.min.js") }}"></script>
<script>
    $(document).on('click','.cancelcounterpayment',function () {
        return confirm('Are you sure?');
    });

    loadApplicationDocs('docListDiv', '');



</script>
