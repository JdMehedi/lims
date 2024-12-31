
<style>
    .wizard > .steps > ul > li {
        width: 20% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard > .content {
        overflow: visible;
    }
    .wizard > .actions
    {
        width: 70% !important;
    }

    /*.wizard > .actions {*/
        /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio{
        cursor: pointer;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .table > thead:first-child > tr:first-child > th {
        font-weight: normal;
        font-size: 16px;
    }

    td {
        font-size: 16px;
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
        height: 120px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }
    .custom_error{
        outline: 1px dashed red;
    }
    .email{
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body{
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .panel-body{
            padding: 0;
        }
        .form-group{
            margin-bottom: 0;
        }
        .wizard>.content>.body label {
            margin-top: .5em;
            margin-bottom: 0;
        }
        .wizard > .actions
        {
            width: 40% !important;
        }
        .tabInput{
            width: 120px;
        }
        .tabInput_sm{
            width: 60px;
        }

        .tabInputDate{
            width: 150px;
        }

        .table_responsive{
            overflow-x: auto;
        }

        .bootstrap-datetimepicker-widget{
            position: relative !important;
            top:0 !important;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

</style>
@include('partials.datatable-css')
@include('partials.datatable-js')

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content load_modal"></div>
    </div>
</div>
@if(in_array($appInfo->status_id,[5,6,17,22]))
    @include('ProcessPath::remarks-modal')
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            {{--Industry registration--}}
            <div class="panel" style="border-radius: 10px;">
                <div class="panel-heading" style="padding: 10px 15px">
                    <p style="font-size: 32px; font-weight: 400">{!!trans('CompanyProfile::messages.industry_reg')!!}</p>
                </div>


                @if(in_array($appInfo->status_id,[5]))
                    <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right" style="margin-right: 3%;">
                            <a data-toggle="modal" data-target="#remarksModal">
                                {!! Form::button('<i class="fa fa-eye"></i>Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                            </a>
                        </div>
                    </div>
                    </div>
                @endif
                <div class="panel-body" style="padding: 0 15px">

                    {!! Form::open(array('url' => url('industry-re-registration/store'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'application_form',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <h3>{!!trans('CompanyProfile::messages.general_information')!!}</h3>
                    <br>

                    {{--                    <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">--}}
                    <input type="hidden" id="openMode" name="openMode" value="edit">

                    {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}

                    <fieldset>

                        {{--Re-reg Info--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('IndustryReRegistration::messages.re_reg_info')!!}</p>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_name', trans('IndustryReRegistration::messages.company_name'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_name') ? 'has-error': ''}}">

                                                {!! Form::text('company_name', null, ['placeholder' => trans("IndustryReRegistration::messages.write_company_name"),
                                               'class' => 'form-control input-md bnEng','id'=>'company_name']) !!}
                                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            {!! Form::label('service_name', trans('IndustryReRegistration::messages.manual_service_name'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('service_name') ? 'has-error': ''}}">
                                                {!! Form::text('service_name', 'পুনঃ নিবন্ধন', ['placeholder' => trans("IndustryReRegistration::messages.write_manual_service_name"),
                                               'class' => 'form-control input-md','id'=>'service_name', 'readonly' => true]) !!}
                                                {!! $errors->first('service_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('manual_reg_number', trans('IndustryReRegistration::messages.manual_reg_number'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('manual_reg_number') ? 'has-error': ''}}">
                                                {!! Form::text('manual_reg_number', null, ['placeholder' => trans("IndustryReRegistration::messages.write_manual_reg_number"),
                                               'class' => 'form-control input-md','id'=>'manual_reg_number']) !!}
                                                {!! $errors->first('manual_reg_number','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('manual_reg_date') ? 'has-error': ''}}">
                                            {!! Form::label('manual_reg_date',trans('IndustryReRegistration::messages.manual_reg_date'),['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('manual_reg_date', null , ['class'=>'form-control input_ban', 'id'=>'manual_reg_date']) !!}
                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                    {!! $errors->first('manual_reg_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">

                                            {!! Form::label('reg_issued_office_id', trans('IndustryReRegistration::messages.reg_issued_office'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('reg_issued_office_id') ? 'has-error': ''}}">
                                                {!! Form::select('reg_issued_office_id', $regOffice, $appInfo->bscic_office_id, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'reg_issued_office_id']) !!}
                                                {!! $errors->first('reg_issued_office_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--General Info--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.general_information')!!}</p>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">
                                                {!! Form::text('company_name_bangla', $appInfo->org_nm_bn, ['placeholder' => trans("CompanyProfile::messages.write_company_name_bangla"),
                                               'class' => 'form-control input-md','id'=>'company_name_bangla']) !!}
                                                {!! $errors->first('company_name_bangla','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_name_english', trans('CompanyProfile::messages.company_name_english'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_name_english') ? 'has-error': ''}}">
                                                {!! Form::text('company_name_english',$appInfo->org_nm, ['placeholder' => trans("CompanyProfile::messages.write_company_name_english"),
                                               'class' => 'form-control input-md','id'=>'company_name_english']) !!}
                                                {!! $errors->first('company_name_english','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('project_name', trans('CompanyProfile::messages.project_name'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('project_name') ? 'has-error': ''}}">
                                                {!! Form::text('project_name',$appInfo->project_nm, ['placeholder' => trans("CompanyProfile::messages.project_name"),
                                               'class' => 'form-control input-md','id'=>'project_name']) !!}
                                                {!! $errors->first('project_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('reg_type_id', trans('CompanyProfile::messages.reg_type'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('reg_type_id') ? 'has-error': ''}}">
                                                {!! Form::select('reg_type_id', $regType, $appInfo->regist_type, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'reg_type_id']) !!}
                                                {!! $errors->first('reg_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_type') ? 'has-error': ''}}">
                                                {!! Form::select('company_type_id', $companyType, $appInfo->org_type, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_type_id']) !!}
                                                {!! $errors->first('company_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('investment_type_id', trans('CompanyProfile::messages.invest_type'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_type') ? 'has-error': ''}}">
                                                {!! Form::select('investment_type_id', $investmentType, $appInfo->invest_type, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investment_type_id']) !!}
                                                {!! $errors->first('investment_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('investing_country_id', trans('CompanyProfile::messages.investing_country'),
                                            ['class'=>'col-md-5']) !!}

                                            <div class="col-md-7 {{$errors->has('investing_country_id') ? 'has-error': ''}}">
                                                {!! Form::select('investing_country_id[]', $country, $investing_country->country_id, ['class' =>'form-control input-md investing_country_id', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investing_country_id']) !!}
                                                {!! $errors->first('investing_country_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('total_investment', trans('CompanyProfile::messages.total_investment'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('total_investment') ? 'has-error': ''}}">
                                                {!! Form::text('total_investment',$appInfo->investment_limit, ['placeholder' => trans("CompanyProfile::messages.write_total_investment"),
                                               'class' => 'form-control  onlyNumber input_ban','id'=>'total_investment']) !!}
                                                {!! $errors->first('total_investment','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('industrial_category_id', trans('CompanyProfile::messages.industrial_class'),
                                            ['class'=>'col-md-3']) !!}<div class="col-md-2"><i class="fa fa-cog" data-toggle="modal" data-target="#feeModal" title="শিল্পের শ্রেণীকরণ সহকারে সরকারি ফি এর বিষয়ে জানতে ক্লিক করুন"></i></div>
                                            <div class="col-md-7 {{$errors->has('industrial_category_id') ? 'has-error': ''}}">
                                                {!! Form::select('industrial_category_id', $industrialCategory, $appInfo->ind_category_id, ['class' =>'form-control input-md', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_category_id']) !!}
                                                {!! $errors->first('industrial_category_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('industrial_sector_id', trans('CompanyProfile::messages.industrial_sector'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('industrial_sector_id') ? 'has-error': ''}}">
                                                {!! Form::select('industrial_sector_id', $industrialSector, $appInfo->ins_sector_id, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_sector_id']) !!}
                                                {!! $errors->first('industrial_sector_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                               {!! Form::label('industrial_sub_sector_id', trans('CompanyProfile::messages.industrial_sub_sector'), ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('industrial_sub_sector_id') ? 'has-error': ''}}">
                                                {!! Form::select('industrial_sub_sector_id', $industrialSubSector, $appInfo->ins_sub_sector_id, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_sub_sector_id']) !!}
                                                {!! $errors->first('industrial_sub_sector_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{--Company office address--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_office_address')!!}</p></div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_division_id', trans('CompanyProfile::messages.division'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_division_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_division_id', $divisions, $appInfo->office_division, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),
'id'=>'company_office_division_id', 'onchange'=>"getDistrictByDivisionId('company_office_division_id', this.value, 'company_office_district_id')"]) !!}
                                                {!! $errors->first('company_office_division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_district_id', trans('CompanyProfile::messages.district'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_district_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_district_id', [], $appInfo->office_district, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),
'id'=>'company_office_district_id', 'onchange'=>"getThanaByDistrictId('company_office_district_id', this.value, 'company_office_thana_id')"]) !!}
                                                {!! $errors->first('company_office_district_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_thana_id', trans('CompanyProfile::messages.thana'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_thana_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_thana_id', [], $appInfo->office_thana, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_office_thana_id']) !!}
                                                {!! $errors->first('company_office_thana_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_postCode', trans('CompanyProfile::messages.post_code'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_postCode', $appInfo->office_postcode, ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'company_office_postCode']) !!}
                                                {!! $errors->first('company_office_postCode','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_office_address', trans('CompanyProfile::messages.address'),
                                            ['class'=>'col-md-2']) !!}
                                            <div class="col-md-10 {{$errors->has('company_office_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                {!! Form::text('company_office_address', $appInfo->office_location, ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md bnEng','id'=>'company_office_address']) !!}
                                                {!! $errors->first('company_office_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_email', $appInfo->office_email, ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email','id'=>'company_office_email']) !!}
                                                {!! $errors->first('company_office_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_mobile', $appInfo->office_mobile, ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'company_office_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_office_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="font-weight: 600;">
                                            {!! Form::checkbox('same_address', 1, ($appInfo->is_same_address == 1) ? true : false, array('id'=>'same_address', 'class'=>'')) !!}
                                            প্রতিষ্ঠানের কার্যালয় এবং কারখানার ঠিকানা একই হলে টিক দিন
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Company factory address--}}
                        <div id="company_factory_div" class="panel panel-default hidden">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_factory_address')!!}</p>
                            </div>

                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_division_id', trans('CompanyProfile::messages.division'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_factory_division_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_division_id', $divisions, $appInfo->factory_division, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),
'id'=>'company_factory_division_id', 'onchange'=>"getDistrictByDivisionId('company_factory_division_id', this.value, 'company_factory_district_id')"]) !!}
                                                {!! $errors->first('company_factory_division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_district_id', trans('CompanyProfile::messages.district'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_factory_district_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_district_id', [], $appInfo->factory_district, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),
'id'=>'company_factory_district_id', 'onchange'=>"getThanaByDistrictId('company_factory_district_id', this.value, 'company_factory_thana_id')"]) !!}
                                                {!! $errors->first('company_factory_district_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_thana_id', trans('CompanyProfile::messages.thana'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_factory_thana_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_thana_id', [], $appInfo->factory_thana, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"), 'id'=>'company_factory_thana_id']) !!}
                                                {!! $errors->first('company_factory_thana_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_postCode', trans('CompanyProfile::messages.post_code'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_postCode', $appInfo->factory_postcode, ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'company_factory_postCode']) !!}
                                                {!! $errors->first('company_factory_postCode','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_factory_address', trans('CompanyProfile::messages.address'),
                                            ['class'=>'col-md-2']) !!}
                                            <div class="col-md-10 {{$errors->has('company_office_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                {!! Form::text('company_factory_address', $appInfo->factory_location, ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md bnEng','id'=>'company_factory_address']) !!}
                                                {!! $errors->first('company_factory_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_factory_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_email', $appInfo->factory_email, ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email','id'=>'company_factory_email']) !!}
                                                {!! $errors->first('company_factory_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_factory_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_mobile', $appInfo->factory_mobile, ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'company_factory_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_factory_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{--Company ceo--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_ceo')!!}</p>
                            </div>
                            <div class="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6 text-right pull-right">
                                            <a style="margin-left: 23px" data-toggle="modal" data-target="#directorModel"
                                               onclick="openModal(this)"
                                               data-action="{{ url('client/company-profile/create-info') }}">
                                                <button style="margin-right: 15px; margin-top: 5px" type="button" class="btn btn-primary ceoInfoDirector " id="addMoreDirector">{!!trans('CompanyProfile::messages.select_identity')!!}</button>
                                                <br>
                                                <br>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('select_directors', trans('CompanyProfile::messages.select_directors'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('select_directors') ? 'has-error': ''}}">
                                                {!! Form::select('select_directors', $companyDirector, $appInfo->director_type, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'select_directors']) !!}
                                                {!! $errors->first('select_directors','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_name', trans('CompanyProfile::messages.name'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_name') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_name', $appInfo->ceo_name, ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                   'class' => 'form-control input-md bnEng','id'=>'company_ceo_name', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_fatherName', trans('CompanyProfile::messages.father_name'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_fatherName') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_fatherName', $appInfo->ceo_father_nm, ['placeholder' => trans("CompanyProfile::messages.write_father_name"),
                                                   'class' => 'form-control input-md','id'=>'company_ceo_fatherName']) !!}
                                                {!! $errors->first('company_ceo_fatherName','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_nationality', trans('CompanyProfile::messages.nationality'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nationality') ? 'has-error': ''}}">
                                                {!! Form::select('company_ceo_nationality', $nationality, $appInfo->nationality, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_ceo_nationality']) !!}
                                                {!! $errors->first('company_ceo_nationality','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="company_ceo_nid_section">
                                            {!! Form::label('company_ceo_nid', trans('CompanyProfile::messages.nid'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_nid', (isset($appInfo->nid) ? $appInfo->nid : ''), ['placeholder' => trans("CompanyProfile::messages.write_nid"),
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'company_ceo_nid', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_nid','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 hidden" id="company_ceo_passport_section">
                                            {!! Form::label('company_ceo_passport', trans('CompanyProfile::messages.passport'),
                                            ['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_passport',(isset($appInfo->passport) ? $appInfo->passport : ''), ['placeholder' => trans("CompanyProfile::messages.write_passport"),
                                               'class' => 'form-control input-md required','id'=>'company_ceo_passport', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_passport','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('company_ceo_dob') ? 'has-error': ''}}">
                                            {!! Form::label('company_ceo_dob',trans('CompanyProfile::messages.dob'),['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('company_ceo_dob', date('d-m-Y', strtotime($appInfo->dob)), ['class'=>'form-control input_ban', 'placeholder' => trans("CompanyProfile::messages.select"),'id'=>'company_ceo_dob', 'readonly'=>true]) !!}
                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                    {!! $errors->first('company_ceo_dob','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_designation_id', trans('CompanyProfile::messages.designation'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_designation_id') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_designation_id', (isset($appInfo->designation) ? $appInfo->designation : ''), ['class'=>'form-control required', 'placeholder' => trans("CompanyProfile::messages.write_designation"), 'id'=>'company_ceo_designation_id','readonly'=>true]) !!}
{{--                                                {!! Form::select('company_ceo_designation_id', $designation, $appInfo->designation, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_designation"),'id'=>'company_ceo_designation_id']) !!}--}}
                                                {!! $errors->first('company_ceo_designation_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_division_id', trans('CompanyProfile::messages.division'),--}}
                                            {{--['class'=>'col-md-5  ']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_division_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_division_id', $divisions, $appInfo->ceo_division, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),--}}
{{--'id'=>'company_ceo_division_id', 'onchange'=>"getDistrictByDivisionId('company_ceo_division_id', this.value, 'company_ceo_district_id')"]) !!}--}}
                                                {{--{!! $errors->first('company_ceo_division_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_district_id', trans('CompanyProfile::messages.district'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_district_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_district_id', [], $appInfo->ceo_district, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),--}}
{{--'id'=>'company_ceo_district_id', 'onchange'=>"getThanaByDistrictId('company_ceo_district_id', this.value, 'company_ceo_thana_id')"]) !!}--}}
                                                {{--{!! $errors->first('company_ceo_district_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_thana_id', trans('CompanyProfile::messages.thana'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_thana_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_thana_id', [], $appInfo->ceo_thana, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_ceo_thana_id']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_postCode', trans('CompanyProfile::messages.post_code'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_postCode') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('company_ceo_postCode', $appInfo->ceo_postcode, ['placeholder' => trans("CompanyProfile::messages.write_post_code"),--}}
                                               {{--'class' => 'form-control input-md  input_ban','id'=>'company_ceo_postCode']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_postCode','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-12">--}}
                                            {{--{!! Form::label('company_ceo_address', trans('CompanyProfile::messages.address'),--}}
                                            {{--['class'=>'col-md-2']) !!}--}}
                                            {{--<div class="col-md-10 {{$errors->has('company_ceo_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">--}}
                                                {{--{!! Form::text('company_ceo_address', $appInfo->ceo_location, ['placeholder' => trans("CompanyProfile::messages.write_address"),--}}
                                               {{--'class' => 'form-control input-md','id'=>'company_ceo_address']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_address','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_email', $appInfo->ceo_email, ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email','id'=>'company_ceo_email']) !!}
                                                {!! $errors->first('company_ceo_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_mobile', $appInfo->ceo_mobile, ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'company_ceo_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_ceo_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-7 col-centered">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="signature-upload">
                                                <div class="col-md-12">
                                                    <label class="center-block image-upload" for="correspondent_signature">
                                                        <figure>
                                                            <img src="{{ (!empty($appInfo->entrepreneur_signature)? url($appInfo->entrepreneur_signature) : url('assets/images/photo_default.png')) }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="correspondent_signature_preview" width="75px"/>
                                                        </figure>
                                                        <input type="hidden" id="correspondent_signature_base64"
                                                               name="correspondent_signature_base64"/>
                                                    </label>
                                                    <p style="font-size: 16px;">
                                                        আপনার স্ক্যান স্বাক্ষরটি এখানে আপলোড করুন বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>
                                                    </p>
                                                    <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.jpg/ .jpeg .png | Maximum 5 MB]
                                                                </span>
                                                    <input type="file" class="form-control  signature-upload-input input-sm   {{ !empty($appInfo->entrepreneur_signature)? '':'required' }}"
                                                           name="correspondent_signature"
                                                           id="correspondent_signature"
                                                           onchange="imageUploadWithCropping(this, 'correspondent_signature_preview', 'correspondent_signature_base64')"
                                                           size="300x80"/>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <p style="font-size: 16px;">
                                                    <a target="_blank" href="https://picresize.com/"> এখানে আপনার  ইমেজ মডিফাই  করতে পারেন</a>
                                                </p>
                                                <span  style="color: #FF7272">প্রয়োজনীয় সকল কাগজপত্র এই স্বাক্ষরের মাধ্যমে স্বাক্ষরিত হতে হবে</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Signature--}}
                        {{--<div class="panel panel-default">--}}
                            {{--<div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.ceo_signature')!!}</p></div>--}}

                            {{--<div class="panel-body" style="padding: 15px 25px;">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('ceo_name', trans('CompanyProfile::messages.name'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('ceo_name') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('ceo_name', $appInfo->entrepreneur_name, ['placeholder' => trans("CompanyProfile::messages.write_name"),--}}
                                                   {{--'class' => 'form-control input-md','id'=>'ceo_name']) !!}--}}
                                                {{--{!! $errors->first('ceo_name','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('ceo_designation_id', trans('CompanyProfile::messages.designation'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('ceo_designation_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('ceo_designation_id', (isset($appInfo->entrepreneur_designation) ? $appInfo->entrepreneur_designation : ''), ['class'=>'form-control required', 'placeholder' => trans("CompanyProfile::messages.write_designation"), 'id'=>'ceo_designation_id']) !!}--}}
{{--                                                {!! Form::select('ceo_designation_id', $designation, $appInfo->entrepreneur_designation, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_designation"),'id'=>'ceo_designation_id']) !!}--}}
                                                {{--{!! $errors->first('ceo_designation_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<br>--}}

                                {{--<div class="col-md-7 col-centered">--}}

                                    {{--<div class="form-group">--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="signature-upload">--}}
                                                {{--<div class="col-md-12">--}}
                                                    {{--<label class="center-block image-upload" for="correspondent_signature">--}}
                                                        {{--<figure>--}}
                                                            {{--<img src="{{ (!empty($appInfo->entrepreneur_signature)? url($appInfo->entrepreneur_signature) : url('assets/images/photo_default.png')) }}"--}}
                                                                 {{--class="img-responsive img-thumbnail"--}}
                                                                 {{--id="correspondent_signature_preview" width="75px"/>--}}
                                                        {{--</figure>--}}
                                                        {{--<input type="hidden" id="correspondent_signature_base64"--}}
                                                               {{--name="correspondent_signature_base64"/>--}}
                                                    {{--</label>--}}
                                                    {{--<p style="font-size: 16px;">--}}
                                                        {{--আপনার স্ক্যান স্বাক্ষরটি এখানে আপলোড করুন বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>--}}
                                                    {{--</p>--}}
                                                    {{--<span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">--}}
                                                                {{--[File Format: *.jpg/ .jpeg .png | Maximum 5 MB]--}}
                                                                {{--</span>--}}
                                                    {{--<input type="file" class="form-control  signature-upload-input input-sm   {{ !empty($appInfo->entrepreneur_signature)? '':'required' }}"--}}
                                                           {{--name="correspondent_signature"--}}
                                                           {{--id="correspondent_signature"--}}
                                                           {{--onchange="imageUploadWithCropping(this, 'correspondent_signature_preview', 'correspondent_signature_base64')"--}}
                                                           {{--size="300x80"/>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="text-center">--}}
                                                {{--<p style="font-size: 16px;">--}}
                                                    {{--<a target="_blank" href="https://picresize.com/"> এখানে আপনার  ইমেজ মডিফাই  করতে পারেন</a>--}}
                                                {{--</p>--}}
                                                {{--<span  style="color: #FF7272">প্রয়োজনীয় সকল কাগজপত্র এই স্বাক্ষরের মাধ্যমে স্বাক্ষরিত হতে হবে</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--Reg Office name--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.pref_reg_office')!!}</p>
                            </div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('pref_reg_office', trans('CompanyProfile::messages.pref_reg_office'),
                                            ['class'=>'col-md-3']) !!}
                                            <div class="col-md-9 {{$errors->has('pref_reg_office') ? 'has-error': ''}}">
                                                {!! Form::select('pref_reg_office', $regOffice, $appInfo->bscic_office_id, ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'pref_reg_office']) !!}
                                                {!! $errors->first('pref_reg_office','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="height: 45px; margin: 0 -25px; background: #F2F3F5;"></div>

                        {{--Reg info--}}
                        <p style="font-size: 32px; font-weight: 400; margin-top: 25px;">{!!trans('CompanyProfile::messages.reg_information')!!}</p>

                        {{--Company main work--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">
                                    ক. {!!trans('CompanyProfile::messages.company_main_works_info')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_main_works', trans('CompanyProfile::messages.company_main_works'),
                                            ['class'=>'col-md-2']) !!}
                                            <div class="col-md-10 {{$errors->has('company_main_works') ? 'has-error': ''}}"
                                                 style="width: 79.5%; float: right">
                                                {!! Form::text('company_main_works', $appInfo->main_activities, ['placeholder' => trans("CompanyProfile::messages.company_main_works"),
                                               'class' => 'form-control input-md bnEng','id'=>'company_main_works']) !!}
                                                {!! $errors->first('company_main_works','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('manufacture_starting_date') ? 'has-error': ''}}">
                                            {!! Form::label('manufacture_starting_date',trans('CompanyProfile::messages.manufacture_starting_date'),['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('manufacture_starting_date', date('d-m-Y', strtotime($appInfo->commercial_operation_dt)), ['class'=>'form-control input_ban', 'id'=>'manufacture_starting_date']) !!}
                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                    {!! $errors->first('manufacture_starting_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('project_deadline') ? 'has-error': ''}}">
                                            {!! Form::label('project_deadline',trans('CompanyProfile::messages.project_deadline'),['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('project_deadline', date('d-m-Y', strtotime($appInfo->project_deadline)), ['class'=>'form-control input_ban','id'=>'project_deadline']) !!}
                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                    {!! $errors->first('project_deadline','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Company annual production--}}
<!--                         <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400; display: inline;">
                                    খ. {!!trans('CompanyProfile::messages.company_annual_production_capacity')!!}</p>
                            </div>

                            <div class="panel-body table-responsive" style="padding: 15px 25px;">
                                <table id="annualProductionTable"
                                       class="table table-bordered dt-responsive"
                                       cellspacing="0" width="100%">
                                    <thead style="background-color: #F7F7F7">
                                    <tr>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.service_name')!!}</th>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.amount')!!}</th>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.price')!!}</th>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.action')!!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($annualProduction)
                                        <?php $inc = 0; ?>
                                        @foreach($annualProduction as $annualProduction)
                                            <tr id="annualProductionRow{{$inc}}" data-number="1">
                                                <td>
                                                    {!! Form::hidden("apc_id[$inc]", $annualProduction->id) !!}

                                                    {!! Form::text("apc_service_name[$inc]", $annualProduction->service_name, ['class' =>'form-control input-md text-left', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'apc_service_name']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("apc_quantity[$inc]", $annualProduction->quantity, ['class' => 'form-control input-md text-left onlyNumber input_ban tabInput', 'placeholder'=> trans("CompanyProfile::messages.write_amount"), 'id'=>'apc_quantity']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("apc_amount_bdt[$inc]", $annualProduction->amount_bdt, ['class' => 'form-control input-md text-left onlyNumber input_ban apc_amount_bdt tabInput', 'onkeyup' => "calculateMachineryTotal('apc_amount_bdt', 'apc_price_total')", 'placeholder' => trans('CompanyProfile::messages.price_lak_taka'), 'id'=>'apc_amount_bdt']) !!}
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($inc == 0) { ?>
                                                    <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                       onclick="addTableRow('annualProductionTable', 'annualProductionRow0');"><i
                                                                class="fa fa-plus"></i></a>
                                                    <?php } else { ?>
                                                    <a href="javascript:void(0);"
                                                       class="btn btn-sm btn-danger removeRow"
                                                       onclick="removeTableRow('annualProductionTable','annualProductionRow{{$inc}}');">
                                                        <i class="fa fa-times"
                                                           aria-hidden="true"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php $inc ++; ?>
                                        @endforeach
                                    @else
                                        <tr id="annualProductionRow" data-number="1">
                                            <td>
                                                {!! Form::text('apc_service_name[]', '', ['class' =>'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'apc_service_name']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('apc_quantity[]', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans("CompanyProfile::messages.write_amount"), 'id'=>'apc_quantity']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('apc_amount_bdt[]', '', ['class' => 'form-control input-md text-center onlyNumber input_ban apc_amount_bdt tabInput', 'onkeyup' => "calculateMachineryTotal('apc_amount_bdt', 'apc_price_total')", 'placeholder' => trans('CompanyProfile::messages.price_lak_taka'), 'id'=>'apc_amount_bdt']) !!}
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                   onclick="addTableRow('annualProductionTable', 'annualProductionRow');"><i
                                                            class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            {!!trans('CompanyProfile::messages.grand_total')!!}
                                        </td>
                                        <td>
                                            {!! Form::text('apc_price_total', (isset($appInfo->apc_price_total) ? $appInfo->apc_price_total : ''), ['class' => 'form-control input-md text-center onlyNumber input_ban', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'apc_price_total']) !!}
                                            {!! $errors->first('apc_price_total','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
 -->


                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400; display: inline;">
                                    খ. {!!trans('CompanyProfile::messages.company_annual_production_capacity')!!}</p>
                            </div>
                    <div class="panel-body " style="padding: 15px 25px;">
                        <?php
                        $elementArray = [
                            ['label' => trans('CompanyProfile::messages.service_name'), 'type' => 'input', 'field_id' => 'service_name','placeholder'=>'নাম লিখুন'],

                            ['label' => trans('CompanyProfile::messages.amount'), 'type' => 'input', 'field_id' => 'quantity','placeholder'=>'পরিমাণ লিখুন','class'=>'onlyNumber input_ban tabInput'
                            ],
                            ['label' => trans('CompanyProfile::messages.price'), 'type' => 'input', 'field_id' => 'amount_bdt','placeholder'=>'মূল্য (লক্ষ টাকায়)','class'=>'onlyNumber input_ban apc_amount_bdt tabInput','js_function'=>"onkeyup=\"calculateMachineryTotal('apc_amount_bdt', 'apc_price_total')\""]
                        ];



                        $tableInfo = ['annualProductionTable', 'templateRow0'];
                        ?>
                        {!! CommonComponent()->addRow($elementArray, $tableInfo,$annualProduction) !!}
                        <table class="table">
                            <tr>

                                <td class="text-right" width="70%">{!!trans('CompanyProfile::messages.grand_total')!!}</td>

                            <td rowspan="1">
                                {!! Form::text('apc_price_total', sset($appInfo->apc_price_total) ? $appInfo->apc_price_total : ''), ['class' => 'form-control input-md text-center onlyNumber input_ban', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'apc_price_total']) !!}
                                            {!! $errors->first('apc_price_total','<span class="help-block">:message</span>') !!}
                            </td>
                            <td class="text-right" width="10%"></td>
                            </tr>

                        </table>
                    </div>
                </div>

                        {{--Sell parcent--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">
                                    গ. {!!trans('CompanyProfile::messages.sell_parcent')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('local_sales', trans('CompanyProfile::messages.local'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('local_sales') ? 'has-error': ''}}">
                                                {!! Form::text('local_sales', $appInfo->sales_local, ['placeholder' => '0%',
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'local_sales_per']) !!}
                                                {!! $errors->first('local_sales','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('foreign_sales', trans('CompanyProfile::messages.foreign'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('foreign') ? 'has-error': ''}}">
                                                {!! Form::text('foreign_sales', $appInfo->sales_foreign, ['placeholder' => '0%',
                                               'class' => 'form-control input-md onlyNumber input_ban','id'=>'foreign_sales_per']) !!}
                                                {!! $errors->first('foreign_sales','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Company manpower--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400; display: inline;">
                                    ঘ. {!!trans('CompanyProfile::messages.company_man_power')!!}</p>
                            </div>

                            <div class="panel-body table-responsive" style="padding: 15px 25px;">
                                <table id=""
                                       class="table table-bordered dt-responsive"
                                       cellspacing="0" width="100%">
                                    <thead style="background-color: #F7F7F7">
                                    <tr>
                                        <th class="text-center"
                                            colspan="3">{!!trans('CompanyProfile::messages.local_bangladeshi')!!}</th>
                                        <th class="text-center"
                                            colspan="3">{!!trans('CompanyProfile::messages.foreign')!!}</th>
                                        <th class="text-center"
                                            colspan="3">{!!trans('CompanyProfile::messages.total')!!}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="manpower">
                                    <tr class="text-center" style="background: #F7F7F7">
                                        <td>{!!trans('CompanyProfile::messages.men')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.women')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.total')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.men')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.women')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.total')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.grand_total')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.local_rate')!!}</td>
                                        <td>{!!trans('CompanyProfile::messages.foreign_rate')!!}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! Form::text('local_male', $appInfo->local_male, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput_sm', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'local_male']) !!}
                                            {!! $errors->first('local_male','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('local_female', $appInfo->local_female, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput_sm', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'local_female']) !!}
                                            {!! $errors->first('local_female','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('local_total', $appInfo->local_total, ['class' => 'form-control input-md text-center input_ban tabInput_sm', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.total"), 'id'=>'local_total']) !!}
                                            {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_male', $appInfo->foreign_male, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput_sm', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'foreign_male']) !!}
                                            {!! $errors->first('foreign_male','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_female', $appInfo->foreign_female, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput_sm', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'foreign_female']) !!}
                                            {!! $errors->first('foreign_female','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('foreign_total', $appInfo->foreign_total, ['class' => 'form-control input-md text-center input_ban tabInput_sm', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.total"), 'id'=>'foreign_total']) !!}
                                            {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('manpower_total', $appInfo->manpower_total, ['class' => 'form-control input-md text-center input_ban tabInput_sm', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.grand_total"), 'id'=>'mp_total']) !!}
                                            {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('manpower_local_ratio', $appInfo->manpower_local_ratio, ['class' => 'form-control input-md text-center input_ban tabInput_sm', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.local_rate"), 'id'=>'mp_ratio_local']) !!}
                                            {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::text('manpower_foreign_ratio', $appInfo->manpower_foreign_ratio, ['class' => 'form-control input-md text-center input_ban tabInput_sm', 'readonly', 'placeholder'=> trans("CompanyProfile::messages.foreign_rate"), 'id'=>'mp_ratio_foreign']) !!}
                                            {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{--Necessary service description--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">
                                    ঙ. {!!trans('CompanyProfile::messages.necessary_services_details')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" cellspacing="0"
                                           width="100%">
                                        <thead style="background: #F7F7F7">
                                        <th class="text-center">{!!trans('CompanyProfile::messages.necessary_services_name')!!}</th>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.has_connectivity_advantage')!!}</th>
                                        <th class="text-center">{!!trans('CompanyProfile::messages.possible_distance_from_connection')!!}</th>
                                        </thead>

                                        <tbody id="">
                                        @foreach($public_utility as $key=>$value)
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{{ $value->name_bn }}</span>

                                                    {!! Form::hidden("ind_reg_utility_id[$key]", $value->id, []) !!}
                                                    {!! Form::hidden("utility_id[$key]", $value->utility_id, []) !!}
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <label class="radio control-label" for=""
                                                               style="margin-right: 15px">
                                                            {!! Form::radio("services_availability[$key]", 1, ($value->services_availability == 1) ? true : false, ['class'=>'', 'id' => '']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>

                                                        <label class="radio control-label" for=""
                                                               style="margin-left: 15px">
                                                            {!! Form::radio("services_availability[$key]", 0, ($value->services_availability == 0) ? true : false, ['class'=>'', 'id' => '']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('utility_distance[]', $value->utility_distance, ['class' => 'form-control input-md onlyNumber input_ban text-center','id'=>'utility_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('utility_distance[]','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("distance_unit[]", ['Meter'=>trans('IndustryNew::messages.Meter'), 'Kilometer'=>trans('IndustryNew::messages.Kilometer')], $value->distance_unit,
["id"=>"distance_unit", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('distance_unit','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Investment--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400; display: inline;">
                                    চ. {!!trans('CompanyProfile::messages.investment')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center"
                                                colspan="3">{!!trans('CompanyProfile::messages.fixed_investment')!!}</th>
                                        </tr>
                                        </thead>

                                        <tbody id="">
                                        <tr>
                                            <td>
                                                <div style="position: relative;">
                                                    <span class="helpTextCom" id="investment_land_label">{!!trans('CompanyProfile::messages.land')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="width:75%;">
                                                            {!! Form::number('local_land_ivst', $appInfo->local_land_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control total_investment_item input-md onlyNumber input_ban','id'=>'local_land_ivst',
                                                             'onkeyup' => 'CalculateTotalInvestmentTk()'
                                                            ]) !!}
                                                            {!! $errors->first('local_land_ivst','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select("local_land_ivst_ccy", $currencyBDT, $appInfo->local_land_ivst_ccy, ["id"=>"local_land_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                            {!! $errors->first('local_land_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>
                                        <tr style="background-color: #F7F7F7">
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="investment_building_label">{!!trans('CompanyProfile::messages.shade')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="width:75%;">
                                                            {!! Form::number('local_building_ivst', $appInfo->local_building_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control input-md total_investment_item onlyNumber input_ban','id'=>'local_building_ivst',
                                                             'onkeyup' => 'CalculateTotalInvestmentTk()']) !!}
                                                            {!! $errors->first('local_building_ivst','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select("local_building_ivst_ccy", $currencyBDT, $appInfo->local_building_ivst_ccy, ["id"=>"local_building_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                            {!! $errors->first('local_building_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="  helpTextCom"
                                                                      id="investment_machinery_equp_label">{!!trans('CompanyProfile::messages.machinery')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="width:75%;">
                                                            {!! Form::number('local_machinery_ivst', $appInfo->local_machinery_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control input-md onlyNumber input_ban total_investment_item','id'=>'local_machinery_ivst',
                                                            'onkeyup' => 'CalculateTotalInvestmentTk()']) !!}
                                                            {!! $errors->first('local_machinery_ivst','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select("local_machinery_ivst_ccy", $currencyBDT, $appInfo->local_machinery_ivst_ccy, ["id"=>"local_machinery_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                            {!! $errors->first('local_machinery_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>
                                        <tr style="background-color: #F7F7F7">
                                            <td>
                                                <div style="position: relative;">
                                                    <span class="helpTextCom"
                                                          id="investment_others_label">{!!trans('CompanyProfile::messages.others')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="width:75%;">
                                                            {!! Form::number('local_others_ivst', $appInfo->local_others_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control input-md onlyNumber input_ban total_investment_item','id'=>'local_others_ivst',
                                                            'onkeyup' => 'CalculateTotalInvestmentTk()']) !!}
                                                            {!! $errors->first('local_others_ivst','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select("local_others_ivst_ccy", $currencyBDT, $appInfo->local_others_ivst_ccy, ["id"=>"local_others_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                            {!! $errors->first('local_others_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="investment_working_capital_label">{!!trans('CompanyProfile::messages.current_investment_3_month')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="width:75%;">
                                                            {!! Form::number('local_wc_ivst', $appInfo->local_wc_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control input-md onlyNumber input_ban total_investment_item','id'=>'local_wc_ivst',
                                                            'onkeyup' => 'CalculateTotalInvestmentTk()']) !!}
                                                            {!! $errors->first('local_wc_ivst','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::select("local_wc_ivst_ccy", $currencyBDT, $appInfo->local_wc_ivst_ccy, ["id"=>"local_wc_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                            {!! $errors->first('local_wc_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr style="background-color: #F7F7F7">
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="investment_total_invst_mi_label">{!!trans('CompanyProfile::messages.grand_total')!!}</span>
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                {!! Form::number('total_fixed_ivst_million', $appInfo->total_fixed_ivst_million, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative onlyNumber input_ban total_fixed_ivst_million','id'=>'total_fixed_ivst_million','readonly']) !!}
                                                {!! $errors->first('total_fixed_ivst_million','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        {{--<tr>--}}
                                            {{--<td>--}}
                                                {{--<div style="position: relative;">--}}
                                                                {{--<span class="helpTextCom"--}}
                                                                      {{--id="investment_total_invst_bd_label">{!!trans('CompanyProfile::messages.grandtotal_lak_bdt')!!}</span>--}}
                                                {{--</div>--}}
                                            {{--</td>--}}
                                            {{--<td colspan="3">--}}
                                                {{--{!! Form::number('total_fixed_ivst', $appInfo->total_fixed_ivst, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative onlyNumber input_ban total_invt_bdt','id'=>'total_invt_bdt','readonly']) !!}--}}
                                                {{--{!! $errors->first('total_fixed_ivst','<span class="help-block">:message</span>') !!}--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        <tr style="background-color: #F7F7F7">
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom  "
                                                                      id="investment_total_invst_usd_label">{!!trans('CompanyProfile::messages.dollar_rate')!!}</span>
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                {!! Form::number('usd_exchange_rate', $appInfo->usd_exchange_rate, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative onlyNumber input_ban','id'=>'usd_exchange_rate','onkeyup' => 'calculateTotalDollar()']) !!}
                                                {!! $errors->first('usd_exchange_rate','<span class="help-block">:message</span>') !!}
                                                <span class="help-text">এক্সচেঞ্জ রেট রেফারেন্স: <a
                                                            href="https://www.bangladesh-bank.org/econdata/exchangerate.php"
                                                            target="_blank">বাংলাদেশ ব্যংক</a>, অনুগ্রহ করে আজকের এক্সচেঞ্জ রেট লিখুন</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="total_invt_dollar_label">{!!trans('CompanyProfile::messages.grandtotal_dollar')!!}</span>
                                                </div>
                                            </td>
                                            <td colspan="3">
                                                {!! Form::number('total_invt_dollar', $appInfo->total_invt_dollar, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative onlyNumber input_ban total_invt_dollar','id'=>'total_invt_dollar','readonly']) !!}
                                                {!! $errors->first('total_fixed_ivst','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="investment_total_fee_bd_label">{!!trans('CompanyProfile::messages.reg_fee')!!}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td width="100%">
                                                            {!! Form::text('total_fee', $appInfo->total_fee, ['class' => 'form-control input-md number input_ban', 'id'=>'total_fee', 'readonly']) !!}
                                                        </td>
                                                        <td>
                                                            <a type="button" class="btn btn-md btn-info"
                                                               data-toggle="modal" data-target="#feeModal">Govt.
                                                                Fees Calculator</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table id="" class="table table-bordered">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center required-star"
                                                colspan="4">{!!trans('CompanyProfile::messages.investment_source')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center" style="background: #FFFFFF">
                                            <td>{!!trans('CompanyProfile::messages.source')!!}</td>
                                            <td>{!!trans('CompanyProfile::messages.taka')!!}</td>
                                            <td>{!!trans('CompanyProfile::messages.dollar')!!}</td>
                                            <td>{!!trans('CompanyProfile::messages.loan_org_country')!!}</td>
                                        </tr>
                                        <tr style="background: #F9F9F9">
                                            <td>
                                                {!!trans('CompanyProfile::messages.ceo_same_invest')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_taka_invest', $appInfo->ceo_taka_invest, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> '0',
    'id'=>'ceo_taka_invest', 'onkeyup'=>"calculateInvSourceTaka()"]) !!}
                                                {!! $errors->first('ceo_taka_invest','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_dollar_invest', $appInfo->ceo_dollar_invest, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> '0',
    'id'=>'ceo_dollar_invest', 'onkeyup'=>"calculateInvSourceDollar()"]) !!}
                                                {!! $errors->first('ceo_dollar_invest','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_loan_org_country', $appInfo->ceo_loan_org_country, ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'ceo_loan_country']) !!}
                                                {!! $errors->first('ceo_loan_org_country','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr style="background: #FFFFFF">
                                            <td>
                                                {!!trans('CompanyProfile::messages.local_loan')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_taka', $appInfo->local_loan_taka, ['class' => 'form-control input-md text-center onlyNumber input_ban', 'placeholder'=> '0',
    'id'=> 'local_loan_taka', 'onkeyup'=>"calculateInvSourceTaka()"]) !!}
                                                {!! $errors->first('local_loan_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_dollar', $appInfo->local_loan_dollar, ['class' => 'form-control input-md text-center onlyNumber input_ban', 'placeholder'=> '0',
    'id'=>'local_loan_dollar', 'onkeyup'=>"calculateInvSourceDollar()"]) !!}
                                                {!! $errors->first('local_loan_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_org_country', $appInfo->local_loan_org_country, ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'local_loan_country']) !!}
                                                {!! $errors->first('local_loan_org_country','<span class="help-block">:message</span>') !!}
                                            </td>

                                        </tr>
                                        <tr style="background: #F9F9F9">
                                            <td>
                                                {!!trans('CompanyProfile::messages.foreign_loan')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_taka', $appInfo->foreign_loan_taka, ['class' => 'form-control input-md text-center onlyNumber input_ban', 'placeholder'=> '0',
    'id'=> 'foreign_loan_taka', 'onkeyup'=>"calculateInvSourceTaka()"]) !!}
                                                {!! $errors->first('foreign_loan_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_dollar', $appInfo->foreign_loan_dollar, ['class' => 'form-control input-md text-center onlyNumber input_ban', 'placeholder'=> '0',
    'id'=>'foreign_loan_dollar', 'onkeyup'=>"calculateInvSourceDollar()"]) !!}
                                                {!! $errors->first('foreign_loan_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_org_country', $appInfo->foreign_loan_org_country, ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'foreign_loan_country']) !!}
                                                {!! $errors->first('foreign_loan_org_country','<span class="help-block">:message</span>') !!}
                                            </td>

                                        </tr>
                                        <tr style="background: #FFFFFF">
                                            <td class="text-right">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('total_inv_taka', $appInfo->total_inv_taka, ['class' => 'form-control input-md input_ban', 'readonly', 'placeholder'=> '0', 'id'=> 'total_inv_taka']) !!}
                                                {!! $errors->first('total_inv_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('total_inv_dollar', $appInfo->total_inv_dollar, ['class' => 'form-control input-md input_ban', 'readonly', 'placeholder'=> '0', 'id'=>'total_inv_dollar']) !!}
                                                {!! $errors->first('total_inv_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="panel">
                                    <div class="panel-heading text-center"
                                         style="background-color: #F7F7F7; border: 1px solid #DDDDDD; border-bottom: none;">
                                        <p
                                           style="font-size: 16px; margin-top: 5px; display: inline;">{!!trans('CompanyProfile::messages.country_wise_loan_source')!!}</p>
                                    </div>
                                    <div class="panel-body" style="padding: 0">
                                        <div class="table_responsive">
                                            <table id="loanSourceTable"
                                                   class="table table-bordered">
                                                <thead style="background-color: #F7F7F7">
                                                <tr style="font-size: 16px;">
                                                    <th style="font-weight: normal;"
                                                        class="text-center">{!!trans('CompanyProfile::messages.country_name')!!}</th>
                                                    <th style="font-weight: normal;"
                                                        class="text-center">{!!trans('CompanyProfile::messages.org_name')!!}</th>
                                                    <th style="font-weight: normal;"
                                                        class="text-center">{!!trans('CompanyProfile::messages.loan_amount')!!}</th>
                                                    <th style="font-weight: normal;"
                                                        class="text-center">{!!trans('CompanyProfile::messages.loan_taking_date')!!}</th>
                                                    <th style="font-weight: normal;" class="text-center">{!!trans('CompanyProfile::messages.action')!!}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($loanSource->isNotEmpty())
                                                    <?php $inc = 0; ?>
                                                    @foreach($loanSource as $loanSource)
                                                        <tr id="loanSourceRow{{$inc}}" data-number="1">
                                                            <td class="text-center">
                                                                {!! Form::hidden("loan_source_id[$inc]", $loanSource->id) !!}

                                                                {!! Form::select("loan_country_id[$inc]", $country, $loanSource->loan_country_id, ['class' =>'form-control input-md tabInput','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'loan_country_id']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text("loan_org_name[$inc]", $loanSource->loan_org_nm, ['class' => 'form-control input-md text-center tabInput', 'placeholder'=> trans("CompanyProfile::messages.org_name"), 'id'=>'loan_org_name']) !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text("loan_amount[$inc]", $loanSource->loan_amount, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder' => trans('CompanyProfile::messages.loan_amount'), 'id'=>'loan_amount']) !!}
                                                            </td>
                                                            <td>
                                                                <div class="datepicker input-group date tabInputDate">
                                                                    {!! Form::text("loan_receive_date[$inc]", date('d-m-Y', strtotime($loanSource->loan_receive_date)), ['class'=>'form-control input_ban', 'id'=>'loan_receive_date']) !!}
                                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                                    {!! $errors->first('loan_receive_date','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ($inc == 0) { ?>
                                                                <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                                   onclick="addTableRow('loanSourceTable', 'loanSourceRow0');"><i
                                                                            class="fa fa-plus"></i></a>
                                                                <?php } else { ?>
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-sm btn-danger removeRow"
                                                                   onclick="removeTableRow('loanSourceTable','loanSourceRow{{$inc}}');">
                                                                    <i class="fa fa-times"
                                                                       aria-hidden="true"></i></a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $inc++; ?>
                                                    @endforeach
                                                @else
                                                    <tr id="loanSourceRow" data-number="1">
                                                        <td class="text-center">
                                                            {!! Form::select('loan_country_id[0]', $country, '', ['class' =>'form-control input-md tabInput','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'loan_country_id']) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('loan_org_name[0]', '', ['class' => 'form-control input-md text-center tabInput', 'placeholder'=> trans("CompanyProfile::messages.org_name"), 'id'=>'loan_org_name']) !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('loan_amount[0]', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder' => trans('CompanyProfile::messages.loan_amount'), 'id'=>'loan_amount']) !!}
                                                        </td>
                                                        <td>
                                                            <div class="datepicker input-group date tabInputDate">
                                                                {!! Form::text('loan_receive_date[0]', '', ['class'=>'form-control input_ban tabInput', 'id'=>'loan_receive_date']) !!}
                                                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                                {!! $errors->first('loan_receive_date','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                               onclick="addTableRow('loanSourceTable', 'loanSourceRow');"><i
                                                                        class="fa fa-plus"></i></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.machinery')!!}</h3>
                    <fieldset>
                        {{--Machinary information--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.locally_collected')!!}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <a data-toggle="modal" data-target="" onclick="openModal(this)"
                                               data-action="{{ url('industry-re-registration/local-machinery/add/'. \App\Libraries\Encryption::encodeId($appInfo->id)) }}">
                                                <button type="button" class="btn btn-primary" style="float: right;">{!!trans('CompanyProfile::messages.add_more')!!}</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="table-responsive">
                                    <table id="localMachineryList"
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="text-center" style="background: #F7F7F7">
                                            {{--                                        <td >{!!trans('CompanyProfile::messages.number')!!}</td>--}}
                                            <th>{!!trans('CompanyProfile::messages.machinery_name')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.n_number')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.price_taka')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.action')!!}</th>
                                        </tr>
                                        </thead>

                                        <tbody></tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="2">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td class="text-center" id="localMachineryTotal">
                                                {!! Form::text('local_machinery_total', '' ,['class' => 'form-control input-md input_ban', 'readonly', 'id'=>'local_machinery_total']) !!}
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.imported')!!}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="pull-right">
                                            <a data-toggle="modal" data-target="" onclick="openModal(this)"
                                               data-action="{{ url('industry-re-registration/imported-machinery/add/'. \App\Libraries\Encryption::encodeId($appInfo->id)) }}">
                                                <button type="button" class="btn btn-primary" style="float: right;">{!!trans('CompanyProfile::messages.add_more')!!}</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="table-responsive">
                                    <table id="importedMachineryList"
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="text-center" style="background: #F7F7F7">
                                            {{--                                        <td >{!!trans('CompanyProfile::messages.number')!!}</td>--}}
                                            <th>{!!trans('CompanyProfile::messages.machinery_name')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.n_number')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.price_taka')!!}</th>
                                            <th>{!!trans('CompanyProfile::messages.action')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-right" colspan="2">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td class="text-center" id="localMachineryTotal">
                                                {!! Form::text('imported_machinery_total', '' ,['class' => 'form-control input-md input_ban', 'readonly', 'id'=>'imported_machinery_total']) !!}
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-justify">
                                    নোটিশঃ আবেদন এর প্রথম ধাপের "চ. বিনিয়োগ" সেকশনের মধ্যে "স্থায়ী বিনিয়োগ" সাব-সেকশনের এর "যন্ত্রপাতি ও সরঞ্জামাদি" এর জন্য যত টাকা দেয়া হবে, আবেদনের দ্বিতীয় ধাপে যন্ত্রপাতি ও সরঞ্জামাদির তথ্য তে স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য + আমদানিকৃত/আমদানিতব্য মোট টাকার পরিমান সমান হতে হবে। অন্যথায় আবেদন গ্রহণ করা যাবেনা।
                                </div>
                            </div>
                        </div>

                        {{--Raw material details--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.raw_material_package_details')!!}</p>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="table-responsive">
                                    <table id=""
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr style="background: #F7F7F7">
                                            <th class="text-center">{!!trans('CompanyProfile::messages.number')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.raw_material_package_source')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.n_number')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.price_taka')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                ১
                                            </td>
                                            <td>
                                                স্থানীয়
                                            </td>
                                            <td>
                                                {!! Form::text('local_raw_material_number', $appInfo->raw_local_number, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.n_number'),
    'id'=> 'local_raw_material_number', 'onkeyup'=>"calculateRawMaterialNumber()"]) !!}
                                                {!! $errors->first('local_raw_material_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_raw_material_price', $appInfo->raw_local_price, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.price_taka'),
    'id'=> 'local_raw_material_price', 'onkeyup'=>"calculateRawMaterialPrice()"]) !!}
                                                {!! $errors->first('local_raw_material_price','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2
                                            </td>
                                            <td>
                                                আমদানিযোগ্য
                                            </td>
                                            <td>
                                                {!! Form::text('import_raw_material_number', $appInfo->raw_imported_number, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.n_number'),
    'id'=> 'import_raw_material_number', 'onkeyup'=>"calculateRawMaterialNumber()"]) !!}
                                                {!! $errors->first('import_raw_material_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('import_raw_material_price', $appInfo->raw_imported_price, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.price_taka'),
    'id'=> 'import_raw_material_price', 'onkeyup'=>"calculateRawMaterialPrice()"]) !!}
                                                {!! $errors->first('import_raw_material_price','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ৩
                                            </td>
                                            <td>
                                                সর্বমোট
                                            </td>
                                            <td>
                                                {!! Form::text('raw_material_total_number', $appInfo->raw_total_number, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'raw_material_total_number']) !!}
                                                {!! $errors->first('raw_material_total_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('raw_material_total_price', $appInfo->raw_total_price, ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.taka'), 'id'=> 'raw_material_total_price']) !!}
                                                {!! $errors->first('raw_material_total_price','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.directors_information')!!}</h3>
                    <fieldset>
                        {{--Company director information--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_director_info')!!}</p>
                                    </div>
                                    <div class="col-md-6">
                                        {{--                                        <div class="pull-left">--}}
                                        {{--                                            <button type="button" class="btn btn-success" id="refreshDirectors" onclick="LoadListOfDirectors()">Refresh Director List</button>--}}
                                        {{--                                        </div>--}}
                                        <div class="pull-right">
                                            <a data-toggle="modal" data-target="#directorModel"
                                               onclick="openModal(this)"
                                               data-action="{{ url('client/company-profile/create-director') }}">
                                                <button type="button" class="btn btn-primary pull-right" style="margin-bottom: 5px" id="addMoreDirector">Add Directors</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <table class="table table-bordered" id="directorList">
                                    <thead style="background-color: #F7F7F7">
                                    <tr>
                                        <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.number')!!}</th>
                                        <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.name')!!}</th>
                                        <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.designation')!!}</th>
                                        <th class="text-center" scope="col">{!! trans('CompanyProfile::messages.nationality') !!}</th>
                                        <th class="text-center" scope="col">{!! trans('CompanyProfile::messages.nid_tin_passport_no') !!}</th>
                                        <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.action')!!}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.attachments')!!}</h3>
                    <fieldset>
                        {{--Applicant info--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.approved_applicant_info')!!}</p>
                            </div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('auth_person_nm', trans('CompanyProfile::messages.name'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_nm') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_nm', $appInfo->auth_person_nm, ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                   'class' => 'form-control input-md','id'=>'auth_person_nm']) !!}
                                                {!! $errors->first('auth_person_nm','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('auth_person_desig', trans('CompanyProfile::messages.designation'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_desig') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_desig', $appInfo->auth_person_desig, ['placeholder' => trans("CompanyProfile::messages.write_designation"),
                                                   'class' => 'form-control input-md','id'=>'auth_person_desig']) !!}
                                                {!! $errors->first('auth_person_desig','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('auth_person_address', trans('CompanyProfile::messages.address'),
                                            ['class'=>'col-md-2']) !!}
                                            <div class="col-md-10 {{$errors->has('auth_person_address') ? 'has-error': ''}}"
                                                 style="width: 79.5%; float: right">
                                                {!! Form::text('auth_person_address', $appInfo->auth_person_address, ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md','id'=>'auth_person_address']) !!}
                                                {!! $errors->first('auth_person_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('auth_person_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_mobile', $appInfo->auth_person_mobile, ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'auth_person_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('auth_person_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('auth_person_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_email') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_email', $appInfo->auth_person_email, ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email ','id'=>'auth_person_email']) !!}
                                                {!! $errors->first('auth_person_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('authorization_letter', trans('CompanyProfile::messages.approved_permission_letter'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('authorization_letter') ? 'has-error': ''}}">
                                                {!! Form::file('authorization_letter', ['class'=>'form-control input-md', 'id' => 'authorization_letter_edit','flag'=>'img']) !!}
                                                {!! $errors->first('authorization_letter','<span class="help-block">:message</span>') !!}
                                                <span class="text-success"
                                                      style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.pdf/ || Max-file size 2MB]
                                                </span>
                                                @isset($appInfo->authorization_letter)
                                                    <a href="{{ '/uploads/'.$appInfo->authorization_letter }}" class="btn btn-sm btn-danger" style="margin-top: 5px">Open</a>
                                                @endisset
                                                <p style="font-size: 12px;">
                                                    <a target="_blank" href="/csv-upload/sample/SamplAuthLetter.pdf">স্যাম্পল ফাইল</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('correspondent_photo', trans('CompanyProfile::messages.image'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="col-sm-9">
                                                    <input type="file" class="form-control input-sm"
                                                           value="{{$appInfo->auth_person_pic}}"
                                                           name="correspondent_photo"
                                                           id="correspondent_photo_edit"
                                                           onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview', 'correspondent_photo_base64')"
                                                           size="300x300"/>
                                                    <span class="text-success"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]
                                                    <p style="font-size: 12px;">
                                                    <a target="_blank" href="https://picresize.com/">আপনার  ইমেজ মডিফাই  করতে পারেন</a>
                                                </p>
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="center-block image-upload" for="correspondent_photo">
                                                        <figure>
                                                            <img src="{{ (!empty($appInfo->auth_person_pic)? url('/'.$appInfo->auth_person_pic) : url('assets/images/photo_default.png')) }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="correspondent_photo_preview"/>
                                                        </figure>
                                                        <input type="hidden" id="correspondent_photo_base64"
                                                               name="correspondent_photo_base64"/>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Necessary attachment--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.necessary_attachment')!!}</p>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <input type="hidden" id="doc_type_key" name="doc_type_key">
                                <div id="docListDiv"></div>
                                <span><p>সংযুক্তির নির্দেশিকা- সার্ভিসের নাম ও প্রতিষ্ঠানের ধরণ নির্ধারণের পর সংযুক্তির তালিকা প্রদর্শিত হবে।</p></span>
                            </div>
                        </div>

                        {{--Announcement--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.announcement')!!}</p>
                            </div>
                            <div class="panel-body" style="padding: 15px 25px;">
                                <p style="color: #452A73;">
                                    বাংলাদেশ ক্ষুদ্র ও কুটির শিল্প করপোরেশন (বিসিক) এর ওয়ান স্টপ সার্ভিস সিস্টেমের
                                    মাধ্যমে জমাকৃত আবেদনপত্রে কোন ধরণের অসঙ্গতি পরিলক্ষিত হলে গণপ্রজাতন্ত্রী বাংলাদেশ
                                    সরকারের আইসিটি আইনের আওতায় দায়বদ্ধ থাকবেন।
                                </p>
                                <br>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                        আমি এই মর্মে ঘোষণা করছি যে উপরে বর্ণিত যাবতীয় তথ্য সঠিক আছে। কোন তথ্য গোপন করা
                                        হয় নাই। কোন তথ্য সঠিক পাওয়া না গেলে কর্তৃপক্ষ শিল্প প্রতিষ্ঠানটির নিবন্ধন বাতিল
                                        করতে পারবেন এবং যে কোন প্রকার প্রশাসনিক ও আইনানুগ ব্যবস্থা গ্রহণ করতে পারবে। আমি
                                        আরো ঘোষণা করছি যে, কর্তৃপক্ষ কর্তৃক সরেজমিনে পরিদর্শনকালে সকল প্রকার সহযোগিতা
                                        প্রদান করতে বাধ্য থাকবো।
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.payment_and_submit')!!}</h3>
                    <fieldset>

                        <div class="panel panel-default" style="border-bottom: none; padding: 7px 25px;">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p
                                        style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('IndustryNew::messages.service_fee_payment')!!}</p>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_name','যোগাযোগ কারীর নাম ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email',' যোগাযোগ কারীর ইমেইল',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md required email']) !!}
                                                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_phone','যোগাযোগকারীর মোবাইল নং ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', Auth::user()->user_mobile, ['class' => 'form-control input-md sfp_contact_phone required helpText15 phone_or_mobile onlyNumber input_ban' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','যোগাযোগের ঠিকানা',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_pay_amount','পে এমাউন্ট ',['class'=>'col-md-3 text-left']) !!}
                                            <div class="col-md-2 "><i class="fa fa-cog" data-toggle="modal" data-target="#feeModal" title="সার্ভিস ফি এর বিষয়ে জানতে ক্লিক করুন"></i></div>
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_pay_amount', $appInfo->sfp_pay_amount, ['class' => 'form-control input-md', 'readonly', 'id'=>'sfp_pay_amount']) !!}
                                                {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_vat_on_pay_amount','পে এমাউন্টের উপর  ভ্যাট ',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_vat_on_pay_amount', $appInfo->sfp_vat_tax, ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_total_amount','মোট টাকা',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_total_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_status','পেমেন্টের অবস্থা',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                @if($appInfo->sfp_payment_status == 0)
                                                    <span class="label label-warning">Pending</span>
                                                @elseif($appInfo->sfp_payment_status == -1)
                                                    <span class="label label-info">In-Progress</span>
                                                @elseif($appInfo->sfp_payment_status == 1)
                                                    <span class="label label-success">Paid</span>
                                                @elseif($appInfo->sfp_payment_status == 2)
                                                    <span class="label label-danger">-Exception</span>
                                                @elseif($appInfo->sfp_payment_status == 3)
                                                    <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                @else
                                                    <span class="label label-warning">invalid status</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--Vat/ tax and service charge is an approximate amount--}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <strong>ভ্যাট / ট্যাক্স </strong> এবং <strong>লেনদেনের চার্জ </strong> আনুমানিক পরিমাণ, সোনালী ব্যাংকের পেমেন্ট সিস্টেমের উপর ভিত্তি করে এগুলি পরিবর্তিত হতে পারে এবং অর্থ প্রদানের পরে তা এখানে দৃশ্যমান হবে।
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    @if(ACL::getAccsessRight('IndustryNew','-E-') && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                        @if(!in_array($appInfo->status_id,[5]))
                            <div class="pull-left">
                                <button type="submit" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                                </button>
                            </div>
                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md"
                                        value="Submit" name="actionBtn">&nbsp;&nbsp;Pay Now&nbsp;&nbsp;
                                    <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info." aria-describedby="tooltip"></i>
                                </button>
                            </div>
                        @endif

                        @if($appInfo->status_id == 5)
                            <div class="pull-left">
                                <span style="display: block; height: 34px">&nbsp;</span>
                            </div>
                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-info btn-md"
                                        value="Submit" name="actionBtn">Re-submit
                                </button>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                </div>

            </div>{{--Industry registration--}}

        </div>
    </div>
</div>

<!-- Modal Govt Payment-->
<div class="modal fade" id="feeModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">সরকারী ফি ক্যালকুলেটর এবং শিল্পের ধরণ</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">সি:নং</th>
                        <th colspan="3" scope="colgroup">ফি এর পর্যায়ক্রম টাকায় </th>
                        <th scope="col">ফি টাকা </th>
                        <th scope="col">শিল্পের ধরণ</th>
                        <th scope="col">সার্ভিস ফি</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($totalFee as $fee)
                        <tr>
                            <td scope="row">{{ $i++ }}</td>
                            <td>{{ $fee->inv_limit_start }}</td>
                            <td>টু</td>
                            <td>{{ $fee->inv_limit_end }}</td>
                            <td>{{ $fee->reg_fee }}</td>
                            <td>{{ $fee->name_bn }}</td>
                            <td>{{ $fee->oss_fee }}</td>

                        </tr>
                    @endforeach

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

@include('partials.image-upload')
<script src="{{ asset('assets/scripts/common-component.js') }}" type="text/javascript"></script>
<script>
    var selectCountry = '';
    $(document).on('keydown','#local_wc_ivst_ccy',function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#usd_exchange_rate').focus();
        }
    })
    $(document).on('keydown','#usd_exchange_rate',function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#ceo_taka_invest').focus();
        }
    })
    $(document).ready(function () {

        @isset($appInfo->office_division)
        getDistrictByDivisionId('company_office_division_id', {{$appInfo->office_division ?? ""}}, 'company_office_district_id', {{$appInfo->office_district ?? ""}});
        @endisset
        @isset($appInfo->office_district)
        getThanaByDistrictId('company_office_district_id', {{$appInfo->office_district ?? ""}}, 'company_office_thana_id', {{$appInfo->office_thana ?? ""}});
        @endisset


        @isset($appInfo->factory_division)
        getDistrictByDivisionId('company_factory_division_id', {{$appInfo->factory_division ?? ""}}, 'company_factory_district_id', {{$appInfo->factory_district ?? ""}});
        @endisset
        @isset($appInfo->factory_district)
        getThanaByDistrictId('company_factory_district_id', {{$appInfo->factory_district ?? ""}}, 'company_factory_thana_id', {{$appInfo->factory_thana ?? ""}});
        @endisset

        @isset($appInfo->ceo_division)
        getDistrictByDivisionId('company_ceo_division_id', {{$appInfo->ceo_division ?? ""}}, 'company_ceo_district_id', {{$appInfo->ceo_district ?? ""}});
        @endisset
        @isset($appInfo->ceo_district)
        getThanaByDistrictId('company_ceo_district_id', {{$appInfo->ceo_district ?? ""}}, 'company_ceo_thana_id', {{$appInfo->ceo_thana ?? ""}});
        @endisset

        // loadApplicationDocs('docListDiv', 'stable_small_forgen');

        var form = $("#application_form").show();
        // form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');

        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            enableFinishButton: false,
            onInit: function (event, currentIndex) {
                $('.actions > ul > li:first-child').attr('style', 'display:none');
                $(".actions a[href='#next']").attr('style', 'background:#3273AB');
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                var imagePath = "{{$appInfo->entrepreneur_signature}}";

                if (imagePath == ""){
                    if ($("#correspondent_signature").hasClass("error") || $("#correspondent_signature").val() == "")
                    {
                        $('.signature-upload').addClass('custom_error');
                    }else{
                        $('.signature-upload').removeClass('custom_error')
                    }
                }


                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                if (newIndex === 2)
                {
                    local_machinery_ivst = $('#local_machinery_ivst').val();

                    local_machinery_total = parseInt($('#local_machinery_total').val());
                    imported_machinery_total = parseInt($('#imported_machinery_total').val());
                    totalsum= local_machinery_total + imported_machinery_total;
                    // alert(local_machinery_ivst);
                    // alert(totalsum);

                    if(local_machinery_ivst != totalsum){
                        swal({
                            type: 'error',
                            text: 'নোটিশঃ আবেদন এর প্রথম ধাপের "চ. বিনিয়োগ" সেকশনের মধ্যে "স্থায়ী বিনিয়োগ" সাব-সেকশনের এর "যন্ত্রপাতি ও সরঞ্জামাদি" এর জন্য যত টাকা দেয়া হবে, আবেদনের দ্বিতীয় ধাপে যন্ত্রপাতি ও সরঞ্জামাদির তথ্য তে স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য + আমদানিকৃত/আমদানিতব্য মোট টাকার পরিমান সমান হতে হবে। অন্যথায় আবেদন গ্রহণ করা যাবেনা।',
                        });
                        return false;
                    }
                    // local_machinery_ivst == local_machinery_total+imported_machinery_total

                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18)
                {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                // if (currentIndex != 0) {
                //     form.find('#save_as_draft').css('display','block');
                // } else {
                //     form.find('#save_as_draft').css('display','none');
                // }

                if (currentIndex > 0) {
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex === 1) {
                    attachmentLoad();
                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                {
                    form.steps("next");
                }
                // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                if(currentIndex === 4){
                    form.find('#submitForm').css('display', 'block');
                    // var add_to_cart_btn = $('<input type="button" value="Add to Cart" class="btn" id="addToCart" style="background: #452A73; color: white; border-radius: 5px;"/>');
                    // add_to_cart_btn.appendTo($('ul[aria-label=Pagination]'));
                    // form.find('#submitForm').css('display', 'block');
                    // $('#addToCart').click(function(){
                    //     swal({
                    //         icon: 'info',
                    //         text: 'Under Processing',
                    //     });
                    //
                    // });
                }
                else {
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('#submitForm').css('display', 'none');
                }
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                alert("Submitted!");
            },
            labels: {
                // cancel: "Cancel",
                // current: "current step:",
                // pagination: "Pagination",
                // finish: 0,
                next: "Next",
                // previous: "Previous",
                // loading: "Loading ..."
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) { element.before(error); },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });




        function attachmentLoad(){
            var reg_type_id = parseInt($("#reg_type_id").val());//order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id+'-'+company_type_id+'-'+industrial_category_id+'-'+investment_type_id

            console.log(key);
            $("#doc_type_key").val(key)
            loadApplicationDocs('docListDiv', key);
        }
    });

    $(document).ready(function () {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{--initail -input mobile plugin script start--}}
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
        {{--initail -input mobile plugin script end--}}

    })

    function mobile_no_validation(id){
        var id = id;
        $("#"+id).on('keyup', function () {
            var countryCode = $("#"+id).intlTelInput("getSelectedCountryData").dialCode;

            if (countryCode === "880"){
                var mobile = $("#"+id).val();
                var reg = /^0/;
                if (reg.test(mobile)) {
                    $("#"+id).val("");
                }

                if(mobile.length !=10){
                    $("#"+id).addClass('error')
                }
            }
        });
    }

    function CalculateTotalInvestmentTk() {
        var land = parseFloat(document.getElementById('local_land_ivst').value);
        var building = parseFloat(document.getElementById('local_building_ivst').value);
        var machine = parseFloat(document.getElementById('local_machinery_ivst').value);
        var other = parseFloat(document.getElementById('local_others_ivst').value);
        var wcCapital = parseFloat(document.getElementById('local_wc_ivst').value);
        var totalInvest = ((isNaN(land) ? 0 : land) + (isNaN(building) ? 0 : building) + (isNaN(machine) ? 0 : machine) + (isNaN(other) ? 0 : other) + (isNaN(wcCapital) ? 0 : wcCapital)).toFixed(2);
        var totalTk = totalInvest;
        document.getElementById('total_fixed_ivst_million').value = totalTk;
        // document.getElementById('total_invt_bdt').value = totalTk;
        if ($('#total_fixed_ivst_million').val() != null){
            calculateTotalDollar();
        }

        var totalFee = '<?php echo json_encode($totalFee); ?>';

        var fee = 0;
        if (totalTk != 0) {
            $.each(JSON.parse(totalFee), function (i, row) {
                if ((totalTk >= parseInt(row.inv_limit_start)) && (totalTk <= parseInt(row.inv_limit_end))) {
                    fee = parseInt(row.reg_fee);
                }
                if (totalTk >= 500000001) {
                    fee = 3000;
                }
            });
        } else {
            fee = 0;
        }
        $("#total_fee").val(fee.toFixed(2));
    }

    function calculateTotalDollar(){
        var total_fixed_ivst_million = $('#total_fixed_ivst_million').val();
        var usd_exchange_rate = $('#usd_exchange_rate').val();
        var usd_amount = (total_fixed_ivst_million/usd_exchange_rate).toFixed(2);
        document.getElementById('total_invt_dollar').value = usd_amount;
    }

    function calculateInvSourceTaka() {
        var ceo_taka_invest = parseFloat(document.getElementById('ceo_taka_invest').value);
        var local_loan_taka = parseFloat(document.getElementById('local_loan_taka').value);
        var foreign_loan_taka = parseFloat(document.getElementById('foreign_loan_taka').value);
        var total_inv_taka = ((isNaN(ceo_taka_invest) ? 0 : ceo_taka_invest) +
            (isNaN(local_loan_taka) ? 0 : local_loan_taka) +
            (isNaN(foreign_loan_taka) ? 0 : foreign_loan_taka)).toFixed(2);
        document.getElementById('total_inv_taka').value = total_inv_taka;
    }

    function calculateInvSourceDollar() {
        var ceo_dollar_invest = parseFloat(document.getElementById('ceo_dollar_invest').value);
        var local_loan_dollar = parseFloat(document.getElementById('local_loan_dollar').value);
        var foreign_loan_dollar = parseFloat(document.getElementById('foreign_loan_dollar').value);
        var total_inv_dollar = ((isNaN(ceo_dollar_invest) ? 0 : ceo_dollar_invest) +
            (isNaN(local_loan_dollar) ? 0 : local_loan_dollar) +
            (isNaN(foreign_loan_dollar) ? 0 : foreign_loan_dollar)).toFixed(2);
        document.getElementById('total_inv_dollar').value = total_inv_dollar;
    }

    function calculateRawMaterialNumber() {
        var local_raw_material_number = parseFloat(document.getElementById('local_raw_material_number').value);
        var import_raw_material_number = parseFloat(document.getElementById('import_raw_material_number').value);
        var raw_material_total_number = ((isNaN(local_raw_material_number) ? 0 : local_raw_material_number) +
            (isNaN(import_raw_material_number) ? 0 : import_raw_material_number)).toFixed(2);
        document.getElementById('raw_material_total_number').value = raw_material_total_number;
    }

    function calculateRawMaterialPrice() {
        var local_raw_material_price = parseFloat(document.getElementById('local_raw_material_price').value);
        var import_raw_material_price = parseFloat(document.getElementById('import_raw_material_price').value);
        var raw_material_total_price = ((isNaN(local_raw_material_price) ? 0 : local_raw_material_price) +
            (isNaN(import_raw_material_price) ? 0 : import_raw_material_price)).toFixed(2);
        document.getElementById('raw_material_total_price').value = raw_material_total_price;
    }

</script>
<script>
    $(document).on('change','.companyInfoChange',function (e){
        $('#same_address').trigger('change');
    })
    $(document).on('blur','.companyInfoInput',function (e){
        $('#same_address').trigger('change');
    })
    $(document).ready(function () {
        var check = $('#same_address').prop('checked');
        if ("{{ $appInfo->is_same_address === 0}}"){
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false){
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function() {

            if(this.checked === false) {
                $('#company_factory_div').removeClass('hidden');
                this.checked = false;
                // $('#company_factory_division_id').val($('#company_office_division_id').val())
                // getDistrictByDivisionId('company_factory_division_id', $('#company_office_division_id').val(), 'company_factory_district_id',$('#company_office_district_id').val());
                // getThanaByDistrictId('company_factory_district_id', $('#company_office_district_id').val(), 'company_factory_thana_id', $('#company_office_thana_id').val());
                // // $('#company_factory_thana_id').val($('#company_office_thana_id').val())
                // $('#company_factory_postCode').val($('#company_office_postCode').val())
                // $('#company_factory_address').val($('#company_office_address').val())
                // $('#company_factory_email').val($('#company_office_email').val())
                // $('#company_factory_mobile').val($('#company_office_mobile').val())
                //
                $("#company_factory_division_id").val("");
                $("#company_factory_district_id").val("");
                $("#company_factory_thana_id").val("");
                $("#company_factory_postCode").val("");
                $("#company_factory_address").val("");
                $("#company_factory_email").val("");
                $("#company_factory_mobile").val("");
            }
            else{
                this.checked = true;
                $('#company_factory_div').addClass('hidden');
                $("#company_factory_division_id").val("").removeClass('error');
                $("#company_factory_district_id").val("").removeClass('error');
                $("#company_factory_thana_id").val("").removeClass('error');
                $("#company_factory_postCode").val("").removeClass('error');
                $("#company_factory_address").val("").removeClass('error');
                $("#company_factory_email").val("").removeClass('error');
                $("#company_factory_mobile").val("").removeClass('error');
            }

        });
        // $("#same_address").trigger('change');

        $("#investment_type_id").change(function () {
            var investment_type_id = $('#investment_type_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-country-by-investment-type') }}",
                data: {
                    investment_type_id: investment_type_id
                },
                success: function (response) {
                    if(investment_type_id == 1){
                        $('#investing_country_id').attr('multiple', 'multiple');
                        //Select2
                        $("#investing_country_id").select2();
                    }else{
                        if ($("#investing_country_id").data('select2')){
                            $("#investing_country_id").select2('destroy');
                        }
                        $('#investing_country_id').removeAttr('multiple');
                    }

                    if(investment_type_id == 3){
                        var option = "";
                    }else{
                        var option = '<option value="">{{trans("CompanyProfile::messages.select")}}</option>';
                    }
                    selectCountry  = "{{$investing_country->country_id}}";
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            var repId = (id.replace(' ',''))
                            if($.inArray(repId,selectCountry.split(',')) != -1){
                                option += '<option value="' + repId + '" selected>' + value + '</option>';
                            }else{
                                option += '<option value="' + repId + '">' + value + '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();

                }
            });
        });
        $("#investment_type_id").trigger('change');

        $("#total_investment").change(function () {
            var total_investment = $('#total_investment').val();
{{--            var service_fee = parseFloat('{{$payment_config->amount}}');--}}
            var vat_percentage = parseFloat('{{$vat_percentage}}');
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-industry-type-by-investment') }}",
                data: {
                    total_investment: total_investment
                },
                success: function (response) {
                    $("#industrial_category_id").val(response.data);
                    if (response.data !== "") {
                        $("#industrial_category_id").find("[value!='" + response.data + "']").prop("disabled", true);
                        $("#industrial_category_id").find("[value='" + response.data + "']").prop("disabled", false);
                    }

                    var oss_fee = parseFloat(response.oss_fee);
                    var vat = (oss_fee/100) * vat_percentage;
                    var total_fee = oss_fee + vat;

                    $("#sfp_pay_amount").val(oss_fee);
                    $("#sfp_vat_on_pay_amount").val(vat);
                    $("#sfp_total_amount").val(total_fee);
                }
            });
        });

        $("#total_investment").trigger('change');

        $("#industrial_sector_id").change(function () {
            var industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function (response) {

                    var option = '<option value="">{{trans("CompanyProfile::messages.select")}}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#industrial_sub_sector_id").html(option);
                    @if(isset($appInfo->ins_sub_sector_id))
                    $("#industrial_sub_sector_id").val("{{$appInfo->ins_sub_sector_id}}").change();
                    @endif
                    $(self).next().hide();
                }
            });
        });

        // $("#industrial_sector_id").trigger('change');

        // Sales (in 100%)
        $("#local_sales_per").on('keyup', function () {
            var local_sales_per = this.value;
            if (local_sales_per <= 100 && local_sales_per >= 0) {
                var cal = 100 - local_sales_per;
                $('#foreign_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        $("#foreign_sales_per").on('keyup', function () {
            var foreign_sales_per = this.value;
            if (foreign_sales_per <= 100 && foreign_sales_per >= 0) {
                var cal = 100 - foreign_sales_per;
                $('#local_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        //------- Manpower start -------//
        $('#manpower').find('input').keyup(function () {
            var local_male = $('#local_male').val() ? parseFloat($('#local_male').val()) : 0;
            var local_female = $('#local_female').val() ? parseFloat($('#local_female').val()) : 0;
            var local_total = parseInt(local_male + local_female);
            $('#local_total').val(local_total);


            var foreign_male = $('#foreign_male').val() ? parseFloat($('#foreign_male').val()) : 0;
            var foreign_female = $('#foreign_female').val() ? parseFloat($('#foreign_female').val()) : 0;
            var foreign_total = parseInt(foreign_male + foreign_female);
            $('#foreign_total').val(foreign_total);

            var mp_total = parseInt(local_total + foreign_total);
            $('#mp_total').val(mp_total);

            var mp_ratio_local = parseFloat(local_total / mp_total);
            var mp_ratio_foreign = parseFloat(foreign_total / mp_total);

//            mp_ratio_local = Number((mp_ratio_local).toFixed(3));
//            mp_ratio_foreign = Number((mp_ratio_foreign).toFixed(3));

//---------- code from bida old
            mp_ratio_local = ((local_total / mp_total) * 100).toFixed(2);
            mp_ratio_foreign = ((foreign_total / mp_total) * 100).toFixed(2);
            // if (foreign_total == 0) {
            //     mp_ratio_local = local_total;
            // } else {
            //     mp_ratio_local = Math.round(parseFloat(local_total / foreign_total) * 100) / 100;
            // }
            // mp_ratio_foreign = (foreign_total != 0) ? 1 : 0;
// End of code from bida old -------------

            $('#mp_ratio_local').val(mp_ratio_local);
            $('#mp_ratio_foreign').val(mp_ratio_foreign);

        });

        LoadListOfDirectors();
        LoadListOfLocalMachinery();
        LoadListOfImportedMachinery();
    })
</script>
<script>
    function openModal(btn) {
        $('#myModal').modal({backdrop: 'static', keyboard: false})
        //e.preventDefault();
        var this_action = btn.getAttribute('data-action');
        if (this_action != '') {
            $.get(this_action, function (data, success) {
                if (success === 'success') {
                    $('#myModal .load_modal').html(data);
                } else {
                    $('#myModal .load_modal').html('Unknown Error!');
                }
                $('#myModal').modal('show', {backdrop: 'static'});
            });
        }
    }

    $(document).ready(function () {
        if ("{{$appInfo->nid != ''}}"){
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_passport").val("");
            $("#company_ceo_nid_section").removeClass("hidden");
        }else{
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid").val("");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url("client/company-profile/load-listof-directors-session") }}",
            type: "POST",
            data: {
                {{--app_id: "{{ Encryption::encodeId($appInfo->id) }}",--}}
                        {{--process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}",--}}
                _token : $('input[name="_token"]').val()
            },
            success: function(response){
                var html = '';
                if (response.responseCode == 1){

                    var edit_url = "{{url('/client/company-profile/edit-director')}}";
                    var delete_url = "{{url('/client/company-profile/delete-director-session')}}";

                    var count = 1;
                    $.each(response.data, function (id, value) {
                        var sl = count ++;

                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' + edit_url + '/' + id + '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a> ';
                        if(sl!=1 ){
                            html +='<a data-action="' + delete_url + '/' + id + '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if(response.ceoInfo !=null){
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth =moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        if (response.ceoInfo.l_father_name != null){
                            $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);
                        }



                        if(response.ceoInfo.nationality == 'Bangladeshi'){
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if(response.ceoInfo.identity_type == 'passport'){
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nid").val('');
                        }else{
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    }else{
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' + '<span class="text-danger">No data available!</span>' + '</td>';
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
                success: function(response){
                    if (response.responseCode == 1){
                        toastr.success(response.msg);
                    }
                }
            });

            LoadListOfLocalMachinery();
            LoadListOfImportedMachinery();
            LoadListOfDirectors();

        } else {
            return false;
        }
    }

    function calculateMachineryTotal(className, totalShowFieldId) {

        var total = 0.00;
        $("." + className).each(function () {
            total = total + (this.value ? parseFloat(this.value) : 0.00);
        })
        $("#" + totalShowFieldId).val(total.toFixed(2));
    }

    //Load list of machinery
    function LoadListOfLocalMachinery() {
        var table = $("#localMachineryList tbody");
        $.ajax({
            url: "{{ url("industry-re-registration/get-local-machinery") }}",
            type: "POST",
            data: {
                app_id: "{{ Encryption::encodeId($appInfo->id) }}",
                _token : $('input[name="_token"]').val()
            },
            success: function(response){
                if (response.responseCode == 1){
                    table.empty();

                    var edit_url = "{{url('industry-re-registration/edit-local-machinery')}}";
                    var delete_url = "{{url('industry-re-registration/delete-local-machinery')}}";

                    $.each(response.data, function (id, value) {
                        table.append(
                            '<tr>'
                            // + '<td class="input_ban">' + value.sl + '</td>'
                            + '<td>' + value.machinery_nm + '</td>'
                            + '<td class="input_ban">' + value.machinery_qty + '</td>'
                            + '<td class="input_ban">' + value.machinery_price + '</td>'
                            + '<td>' +
                            '<a data-toggle="modal" data-target="" onclick="openModal(this)" data-action="' + edit_url + '/' + value.id + '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a> ' +
                            '<a data-action="' + delete_url + '/' + value.id + '/' + value.app_id + '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>' +
                            '</td>'
                            + '</tr>'
                        );
                    });

                    $("#localMachineryList").DataTable();
                    $('#local_machinery_total').val(response.localTotal);
                } else {
                    table.append(
                        '<tr>'
                        + '<td colspan="5" class="text-center">' + '<span class="text-danger">No data available!</span>' + '</td>'
                        + '</tr>'
                    );
                }
            }
        });
    }

    function LoadListOfImportedMachinery() {
        var table = $("#importedMachineryList tbody");
        $.ajax({
            url: "{{ url("industry-re-registration/get-imported-machinery") }}",
            type: "POST",
            data: {
                app_id: "{{ Encryption::encodeId($appInfo->id) }}",
                _token : $('input[name="_token"]').val()
            },
            success: function(response){
                if (response.responseCode == 1){
                    table.empty();

                    var edit_url = "{{url('industry-re-registration/edit-imported-machinery')}}";
                    var delete_url = "{{url('industry-re-registration/delete-imported-machinery')}}";

                    $.each(response.data, function (id, value) {
                        table.append(
                            '<tr>'
                            // + '<td class="input_ban">' + value.sl + '</td>'
                            + '<td>' + value.machinery_nm + '</td>'
                            + '<td class="input_ban">' + value.machinery_qty + '</td>'
                            + '<td class="input_ban">' + value.machinery_price + '</td>'
                            + '<td>' +
                            '<a data-toggle="modal" data-target="" onclick="openModal(this)" data-action="' + edit_url + '/' + value.id + '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a> ' +
                            '<a data-action="' + delete_url + '/' + value.id + '/' + value.app_id + '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>' +
                            '</td>'
                            + '</tr>'
                        );
                    });

                    $("#importedMachineryList").DataTable();
                    $('#imported_machinery_total').val(response.importedTotal);
                } else {
                    table.append(
                        '<tr>'
                        + '<td colspan="5" class="text-center">' + '<span class="text-danger">No data available!</span>' + '</td>'
                        + '</tr>'
                    );
                }
            }
        });
    }
</script>
<script type="text/javascript" src="{{ asset("assets/scripts/custom.min.js") }}"></script>
