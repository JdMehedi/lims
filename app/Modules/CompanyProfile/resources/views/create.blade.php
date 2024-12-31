@extends('layouts.admin')
@section('title', 'Company Profile | Bangladesh Telecommunication Regulatory Commission')
@section('header-resources')
    <link rel="stylesheet" href="{{ asset("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}" />
    <link rel="stylesheet" href="{{ mix("assets/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.min.css") }}"/>
    <style>
        .col-centered{
            float: none;
            margin: 0 auto;
        }
        form label {
            font-weight: normal;
            font-size: 16px;
        }
        .table > thead:first-child > tr:first-child > th {
            font-weight: normal;
            font-size: 16px;
        }
        td{
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
            z-index: 0;
            background-color: blue;
        }
        .sign_div{
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
            height: 160px;
            width: 100%;
            max-width: 100%;
            padding: 5px 10px;
            font-size: 1rem;
            text-align: center;
            color: #000;
            background-color: #F5FAFE;
            border-radius: 0 !important;
            border: 0;
            /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
            font-weight: 400;
            outline: 1px dashed #ccc;
            margin-bottom: 5px;
        }
        .custom_error{
            outline: 1px dashed red;
        }

        .image-upload figure {
            position: relative;
            cursor: pointer;
        }

        .image-upload figure figcaption {
            position: absolute;
            bottom: 0;
            color: #fff;
            width: 100%;
            padding-left: 9px;
            padding-bottom: 5px;
            text-shadow: 0 0 10px #000;
        }
        .email{
            font-family: Arial !important;
        }

        @media (min-width: 767px) {
            .addressField{
                width: 79.5%;
                float: right
            }
        }
        @media (max-width: 480px) {
            .form-group{
                margin: 0;
            }

            label {
                font-weight: normal;
                font-size: 14px;
                margin-top: 5px;
            }
        }

    </style>
@endsection

@section('content')
    <div class="modal fade" id="ImageUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> Photo resize</h4>
                </div>
                <div class="modal-body">
                    <div id="upload_crop_area" class="center-block" style="padding-bottom: 25px"></div>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <div id="cropping_msg" class="alert alert-info text-center hidden">
                        <i class="fa fa-spinner fa-pulse"></i> Please wait, Face detecting
                    </div>
                    <div id="button_area">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="cropImageBtn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    @include('partials.messages')
    <br>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 col-lg-12">
                {!! Form::open(array('url' => '/client/company-profile/store', 'method' => 'post', 'class' =>
                        'form-horizontal', 'id' => 'companyProfileAddForm',
                        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                {{--Company Profile--}}
                <div class="panel" style="border-radius: 10px;">
                    <div class="panel-heading" style="padding: 10px 15px"><p style="font-size: 32px; font-weight: 400">{!!trans('CompanyProfile::messages.company_profile')!!}</p></div>
                    <div class="panel-body" style="padding: 0 15px">
                        {{--General Info--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.general_information')!!}</p></div>
                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::text('company_name_bangla', '', ['placeholder' => trans("CompanyProfile::messages.write_company_name_bangla"),--}}
{{--                                               'class' => 'form-control input-md required bnEng','id'=>'company_name_bangla']) !!}--}}
{{--                                                {!! $errors->first('company_name_bangla','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="col-md-6">
                                            {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_type') ? 'has-error': ''}}">
                                                {!! Form::select('company_type_id', $companyType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_type_id']) !!}
                                                {!! $errors->first('company_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('company_name_english', trans('CompanyProfile::messages.company_name_english'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('company_name_english') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::text('company_name_english','', ['placeholder' => trans("CompanyProfile::messages.write_company_name_english"),--}}
{{--                                               'class' => 'form-control input-md required bnEng','id'=>'company_name_english']) !!}--}}
{{--                                                {!! $errors->first('company_name_english','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('reg_type_id', trans('CompanyProfile::messages.reg_type'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('reg_type_id') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('reg_type_id', $regType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'reg_type_id']) !!}--}}
{{--                                                {!! $errors->first('reg_type_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('company_type') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('company_type_id', $companyType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_type_id']) !!}--}}
{{--                                                {!! $errors->first('company_type_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('investment_type_id', trans('CompanyProfile::messages.invest_type'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('company_type') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('investment_type_id', $investmentType, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investment_type_id']) !!}--}}
{{--                                                {!! $errors->first('investment_type_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('investing_country_id', trans('CompanyProfile::messages.investing_country'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('investing_country_id') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('investing_country_id[]', [], '', ['class' =>'form-control input-md investing_country_id required', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investing_country_id']) !!}--}}
{{--                                                {!! $errors->first('investing_country_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('total_investment', trans('CompanyProfile::messages.total_investment'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('total_investment') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::text('total_investment','', ['placeholder' => trans("CompanyProfile::messages.write_total_investment"),--}}
{{--                                               'class' => 'form-control input-md onlyNumber input_ban required','id'=>'total_investment']) !!}--}}
{{--                                                {!! $errors->first('total_investment','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('industrial_category_id', trans('CompanyProfile::messages.industrial_class'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('industrial_category_id') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('industrial_category_id', $industrialCategory, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_category_id']) !!}--}}
{{--                                                {!! $errors->first('industrial_category_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('industrial_sector_id', trans('CompanyProfile::messages.industrial_sector'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('industrial_sector_id') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('industrial_sector_id', $industrialSector, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_sector_id']) !!}--}}
{{--                                                {!! $errors->first('industrial_sector_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            {!! Form::label('industrial_sub_sector_id', trans('CompanyProfile::messages.industrial_sub_sector'),--}}
{{--                                            ['class'=>'col-md-4 required-star']) !!}--}}
{{--                                            <div class="col-md-8 {{$errors->has('industrial_sub_sector_id') ? 'has-error': ''}}">--}}
{{--                                                {!! Form::select('industrial_sub_sector_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_sub_sector_id']) !!}--}}
{{--                                                {!! $errors->first('industrial_sub_sector_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

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
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_division_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_division_id', $divisions, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_division"),
'id'=>'company_office_division_id', 'onchange'=>"getDistrictByDivisionId('company_office_division_id', this.value, 'company_office_district_id')"]) !!}
                                                {!! $errors->first('company_office_division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_district_id', trans('CompanyProfile::messages.district'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_district_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_district_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_district"),
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
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_thana_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_office_thana_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_office_thana_id']) !!}
                                                {!! $errors->first('company_office_thana_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_postCode', trans('CompanyProfile::messages.post_code'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                               'class' => 'form-control input-md onlyNumber  required','id'=>'company_office_postCode']) !!}
                                                {!! $errors->first('company_office_postCode','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_office_address', trans('CompanyProfile::messages.address'),
                                            ['class'=>'col-md-2 required-star']) !!}
                                            <div class="col-md-10 addressField {{$errors->has('company_office_address') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md required bnEng','id'=>'company_office_address']) !!}
                                                {!! $errors->first('company_office_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email required','id'=>'company_office_email']) !!}
                                                {!! $errors->first('company_office_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_office_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_office_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md  onlyNumber required','id'=>'company_office_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_office_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label style="font-weight: 600;">
                                            {!! Form::checkbox('same_address',1,null, array('id'=>'same_address', 'class'=>'', 'checked' => true)) !!}
                                            প্রতিষ্ঠানের কার্যালয় একং কারখানার ঠিকানা একই হলে টিক দিন
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Company factory address--}}
                        <div id="company_factory_div" class="panel panel-default hidden">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_factory_address')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_division_id', trans('CompanyProfile::messages.division'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_factory_division_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_division_id', $divisions, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_division"),
'id'=>'company_factory_division_id', 'onchange'=>"getDistrictByDivisionId('company_factory_division_id', this.value, 'company_factory_district_id')"]) !!}
                                                {!! $errors->first('company_factory_division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_district_id', trans('CompanyProfile::messages.district'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_factory_district_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_district_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_district"),
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
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_factory_thana_id') ? 'has-error': ''}}">
                                                {!! Form::select('company_factory_thana_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_thana"), 'id'=>'company_factory_thana_id']) !!}
                                                {!! $errors->first('company_factory_thana_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_postCode', trans('CompanyProfile::messages.post_code'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                               'class' => 'form-control input-md onlyNumber  required','id'=>'company_factory_postCode']) !!}
                                                {!! $errors->first('company_factory_postCode','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_address', trans('CompanyProfile::messages.address'), ['class' => 'col-md-2']) !!}
                                            <div class="col-md-10 {{ $errors->has('auth_person_address') ? 'has-error' : '' }}"
                                                 style="width: 79.5%; float: right">
                                                {!! Form::text('auth_person_address', '', ['placeholder' => trans('CompanyProfile::messages.write_address'), 'class' => 'form-control input-md', 'id' => 'auth_person_address']) !!}
                                                {!! $errors->first('auth_person_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_factory_address', trans('CompanyProfile::messages.address'),
                                            ['class'=>'col-md-2 required-star']) !!}
                                            <div class="col-md-10 addressField {{$errors->has('company_factory_address') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md required bnEng','id'=>'company_factory_address']) !!}
                                                {!! $errors->first('company_factory_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_factory_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email required','id'=>'company_factory_email']) !!}
                                                {!! $errors->first('company_factory_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_factory_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_factory_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_factory_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md  required','id'=>'company_factory_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
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
                                <div class="row">
                                    <div class="col-md-10">
                                        <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_ceo')!!}</p>
                                    </div>
    {{--                                <div class="col-md-6">--}}
    {{--                                    <div class="pull-right">--}}
    {{--                                        <a data-toggle="modal" data-target="#directorModel"--}}
    {{--                                           onclick="openModal(this)"--}}
    {{--                                           data-action="{{ url('client/company-profile/create-director') }}">--}}
    {{--                                            <button type="button" class="btn btn-primary pull-right" style="margin-bottom: 5px" id="addMoreDirector">{!!trans('CompanyProfile::messages.select_identity')!!}</button>--}}
    {{--                                        </a>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
                                </div>
                            </div>

                            <div class="panel-body hidden" style="padding: 15px 25px;" id="ceoInfoDIV">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('select_directors', trans('CompanyProfile::messages.select_directors'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('select_directors') ? 'has-error': ''}}">
                                                {!! Form::select('select_directors', $companyDirector, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'select_directors']) !!}
                                                {!! $errors->first('select_directors','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_name', trans('CompanyProfile::messages.name'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_ceo_name') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_name','', ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                   'class' => 'form-control input-md required bnEng','id'=>'company_ceo_name', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_fatherName', trans('CompanyProfile::messages.father_name'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_ceo_fatherName') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_fatherName','', ['placeholder' => trans("CompanyProfile::messages.write_father_name"),
                                                   'class' => 'form-control input-md required bnEng','id'=>'company_ceo_fatherName']) !!}
                                                {!! $errors->first('company_ceo_fatherName','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_nationality', trans('CompanyProfile::messages.nationality'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('ceo_nationality') ? 'has-error': ''}}">
                                                {!! Form::select('company_ceo_nationality', $nationality, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_ceo_nationality']) !!}
                                                {!! $errors->first('company_ceo_nationality','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="company_ceo_nid_section">
                                            {!! Form::label('company_ceo_nid', trans('CompanyProfile::messages.nid'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_nid','', ['placeholder' => trans("CompanyProfile::messages.write_nid"),
                                               'class' => 'form-control input-md onlyNumber required ','id'=>'company_ceo_nid', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_nid','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 hidden" id="company_ceo_passport_section">
                                            {!! Form::label('company_ceo_passport', trans('CompanyProfile::messages.passport'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_passport','', ['placeholder' => trans("CompanyProfile::messages.write_passport"),
                                               'class' => 'form-control input-md required','id'=>'company_ceo_passport', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_passport','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('company_ceo_dob') ? 'has-error': ''}}">
                                            {!! Form::label('company_ceo_dob',trans('CompanyProfile::messages.dob'),['class'=>'col-md-4']) !!}
                                            <div class=" col-md-8">
                                                <div class="ceoDP input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('company_ceo_dob', '', ['class'=>'form-control input_ban required', 'placeholder' => trans("CompanyProfile::messages.select"), 'data-rule-maxlength'=>'40', 'id'=>'company_ceo_dob', 'readonly'=>true]) !!}
                                                    <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                    {!! $errors->first('company_ceo_dob','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_designation_id', trans('CompanyProfile::messages.designation'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_ceo_designation_id') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_designation_id', '', ['class'=>'form-control required bnEng', 'placeholder' => trans("CompanyProfile::messages.write_designation"), 'data-rule-maxlength'=>'40', 'id'=>'ceo_designation', 'readonly'=>true]) !!}
{{--                                                {!! Form::select('company_ceo_designation_id', $designation, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_designation"),'id'=>'ceo_designation']) !!}--}}
                                                {!! $errors->first('company_ceo_designation_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_division_id', trans('CompanyProfile::messages.division'),--}}
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('company_ceo_division_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_division_id', $divisions, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_division"),--}}
{{--'id'=>'company_ceo_division_id', 'onchange'=>"getDistrictByDivisionId('company_ceo_division_id', this.value, 'company_ceo_district_id')"]) !!}--}}
                                                {{--{!! $errors->first('company_ceo_division_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_district_id', trans('CompanyProfile::messages.district'),--}}
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('company_ceo_district_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_district_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_district"),--}}
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
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('company_ceo_thana_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_thana_id', [], '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_ceo_thana_id']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_postCode', trans('CompanyProfile::messages.post_code'),--}}
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('company_ceo_postCode') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('company_ceo_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),--}}
                                               {{--'class' => 'form-control input-md onlyNumber input_ban required','id'=>'company_ceo_postCode']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_postCode','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-12">--}}
                                            {{--{!! Form::label('company_ceo_address', trans('CompanyProfile::messages.address'),--}}
                                            {{--['class'=>'col-md-2 required-star']) !!}--}}
                                            {{--<div class="col-md-10 {{$errors->has('company_ceo_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">--}}
                                                {{--{!! Form::text('company_ceo_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),--}}
                                               {{--'class' => 'form-control input-md required bnEng','id'=>'company_ceo_address']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_address','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_email', trans('CompanyProfile::messages.email'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email required','id'=>'company_ceo_email']) !!}
                                                {!! $errors->first('company_ceo_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('company_ceo_mobile', trans('CompanyProfile::messages.mobile_no'),
                                            ['class'=>'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{$errors->has('company_ceo_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md  onlyNumber required','id'=>'company_ceo_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_ceo_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-6 col-centered">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="signature-upload">
                                                <div class="col-md-12">
                                                    <label class="center-block image-upload" for="correspondent_signature">
                                                        <figure>
                                                            <img src="{{ url('assets/images/photo_default.png') }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="correspondent_signature_preview" width="75px"/>
                                                        </figure>
                                                        <input type="hidden" id="correspondent_signature_base64"
                                                               name="correspondent_signature_base64" class="required"/>
                                                    </label>
                                                    <p style="font-size: 16px;">
                                                        আপনার স্ক্যান স্বাক্ষরটি এখানে আপলোড করুন  বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>
                                                    </p>
                                                    <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.jpg/ .jpeg .png | Maximum 5 MB]
                                                                </span>
                                                    <input type="file" class="form-control signature-upload-input input-sm required"
                                                           name="correspondent_signature"
                                                           id="correspondent_signature"
                                                           onchange="imageUploadWithCropping(this, 'correspondent_signature_preview', 'correspondent_signature_base64')"
                                                           size="300x80"/>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <p style="font-size: 16px;">
                                                    <a target="_blank" href="https://picresize.com/">আপনার  ইমেজ মডিফাই  করতে পারেন</a>
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
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('ceo_name') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('ceo_name','', ['placeholder' => trans("CompanyProfile::messages.write_name"),--}}
                                                   {{--'class' => 'form-control input-md required bnEng','id'=>'ceo_name']) !!}--}}
                                                {{--{!! $errors->first('ceo_name','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('ceo_designation_id', trans('CompanyProfile::messages.designation'),--}}
                                            {{--['class'=>'col-md-4 required-star']) !!}--}}
                                            {{--<div class="col-md-8 {{$errors->has('ceo_designation_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('ceo_designation_id', '', ['class'=>'form-control required bnEng', 'placeholder' => trans("CompanyProfile::messages.write_designation"), 'data-rule-maxlength'=>'40', 'id'=>'ceo_designation']) !!}--}}
{{--                                                {!! Form::select('ceo_designation_id', $designation, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select_designation"),'id'=>'ceo_designation']) !!}--}}
                                                {{--{!! $errors->first('ceo_designation_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<br>--}}
                                {{--<div class="col-md-6 col-centered">--}}

                                    {{--<div class="form-group">--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="signature-upload">--}}
                                                {{--<div class="col-md-12">--}}
                                                    {{--<label class="center-block image-upload" for="correspondent_signature">--}}
                                                        {{--<figure>--}}
                                                            {{--<img src="{{ url('assets/images/photo_default.png') }}"--}}
                                                                 {{--class="img-responsive img-thumbnail"--}}
                                                                 {{--id="correspondent_signature_preview" width="75px"/>--}}
                                                        {{--</figure>--}}
                                                        {{--<input type="hidden" id="correspondent_signature_base64"--}}
                                                               {{--name="correspondent_signature_base64" class="required"/>--}}
                                                    {{--</label>--}}
                                                    {{--<p style="font-size: 16px;">--}}
                                                        {{--আপনার স্ক্যান স্বাক্ষরটি এখানে আপলোড করুন  বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>--}}
                                                    {{--</p>--}}
                                                    {{--<span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">--}}
                                                                {{--[File Format: *.jpg/ .jpeg .png | Maximum 5 MB]--}}
                                                                {{--</span>--}}
                                                    {{--<input type="file" class="form-control signature-upload-input input-sm required"--}}
                                                           {{--name="correspondent_signature"--}}
                                                           {{--id="correspondent_signature"--}}
                                                           {{--onchange="imageUploadWithCropping(this, 'correspondent_signature_preview', 'correspondent_signature_base64')"--}}
                                                           {{--size="300x80"/>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="text-center">--}}
                                                {{--<p style="font-size: 16px;">--}}
                                                    {{--<a target="_blank" href="https://picresize.com/">আপনার  ইমেজ মডিফাই  করতে পারেন</a>--}}
                                                {{--</p>--}}
                                                {{--<span  style="color: #FF7272">প্রয়োজনীয় সকল কাগজপত্র এই স্বাক্ষরের মাধ্যমে স্বাক্ষরিত হতে হবে</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                        {{--</div>--}}

                        {{--Reg Office name--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.pref_reg_office')!!}</p></div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('pref_reg_office', trans('CompanyProfile::messages.pref_reg_office'),
                                            ['class'=>'col-md-3 required-star']) !!}
                                            <div class="col-md-9 {{$errors->has('pref_reg_office') ? 'has-error': ''}}">
                                                {!! Form::select('pref_reg_office', $regOffice, '', ['class' =>'form-control input-md required','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'pref_reg_office']) !!}
                                                {!! $errors->first('pref_reg_office','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Company main work--}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;">
                                <p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_main_works')!!}</p>
                            </div>

                            <div class="panel-body" style="padding: 15px 25px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('company_main_works', trans('CompanyProfile::messages.company_main_works'),
                                            ['class'=>'col-md-2 required-star']) !!}
                                            <div class="col-md-10 addressField {{$errors->has('company_main_works') ? 'has-error': ''}}">
                                                {!! Form::text('company_main_works','', ['placeholder' => trans("CompanyProfile::messages.company_main_works"),
                                               'class' => 'form-control input-md required bnEng','id'=>'company_main_works']) !!}
                                                {!! $errors->first('company_main_works','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6 {{$errors->has('manufacture_starting_date') ? 'has-error': ''}}">--}}
                                            {{--{!! Form::label('manufacture_starting_date',trans('CompanyProfile::messages.manufacture_starting_date'),['class'=>'col-md-4']) !!}--}}
                                            {{--<div class="col-md-8">--}}
                                                {{--<div class="manufacture_starting_dateDP input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">--}}
                                                    {{--{!! Form::text('manufacture_starting_date', '', ['class'=>'form-control input_ban required',  'data-rule-maxlength'=>'40', 'id'=>'manufacture_starting_date']) !!}--}}
                                                    {{--<span class="input-group-addon">--}}
                                    {{--<span class="fa fa-calendar"></span>--}}
                                {{--</span>--}}
                                                    {{--{!! $errors->first('manufacture_starting_date','<span class="help-block">:message</span>') !!}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6 {{$errors->has('project_deadline') ? 'has-error': ''}}">--}}
                                            {{--{!! Form::label('project_deadline',trans('CompanyProfile::messages.project_deadline'),['class'=>'col-md-4']) !!}--}}
                                            {{--<div class=" col-md-8">--}}
                                                {{--<div class="project_deadlineDP input-group date">--}}
                                                    {{--{!! Form::text('project_deadline', '', ['class'=>'form-control input_ban required', 'data-rule-maxlength'=>'40', 'id'=>'project_deadline']) !!}--}}
                                                    {{--<span class="input-group-addon">--}}
                                    {{--<span class="fa fa-calendar"></span>--}}
                                {{--</span>--}}
                                                    {{--{!! $errors->first('project_deadline','<span class="help-block">:message</span>') !!}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>



                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn pull-right" name="sumbit" style="color: white; background: #55BC7A; width: 100px">
                                    Submit
                                </button>
                            </div>
                        </div>

                    </div>

                </div>{{--Company Profile End--}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
            @include('partials.image-upload')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script src="{{ mix("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/custom.js") }}"></script>

    <script>
        $(document).on('change','.companyInfoChange',function (e){
            $('#same_address').trigger('change');
        })
        $(document).on('blur','.companyInfoInput',function (e){
            $('#same_address').trigger('change');
        })
        $(document).ready(function () {
            $("#companyProfileAddForm").validate({
                rules: {
                    ".required": {required: true, maxlength: 1}
                },
                errorPlacement: function () {
                    return true;
                },
            });
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.ceoDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MM-YYYY',
                maxDate: 'now',
                // minDate: '01/01/' + (yyyy - 110)
            });

            $('.manufacture_starting_dateDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MM-YYYY',
                // maxDate: '01/01/' + (yyyy + 110),
                minDate: '01/01/' + (yyyy - 110)
            });

            $('.project_deadlineDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MM-YYYY',
                // maxDate: '01/01/' + (yyyy + 110),
                minDate: '01/01/' + (yyyy - 110)
            });

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
                    // $("#company_factory_division_id").prop('disabled', true);
                    // $("#company_factory_district_id").prop('disabled', true);
                    // $("#company_factory_thana_id").prop('disabled', true);
                    // $("#company_factory_postCode").prop('disabled', true);
                    // $("#company_factory_address").prop('disabled', true);
                    // $("#company_factory_email").prop('disabled', true);
                    // $("#company_factory_mobile").prop('disabled', true);
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
            {{--initail -input mobile plugin script end--}}
            $('#companyProfileAddForm').on('submit', function () {
                if ($("#correspondent_signature").hasClass("error") || $("#correspondent_signature").val() == ""){
                    $('.signature-upload').addClass('custom_error')
                }else{
                    $('.signature-upload').removeClass('custom_error')
                }
            })

        })

    </script>

    <script>
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

                    var option = '<option value="">{{trans("CompanyProfile::messages.select")}}</option>';

                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(investment_type_id == 3){
                                option = '<option value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();
                }
            });
        });
    </script>

    <script>
        function openModal(btn) {
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
    </script>

    <script>
        $("#total_investment").change(function () {
            var total_investment = $('#total_investment').val();
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
                }
            });
        });
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
                }
            });
        }
    </script>

    <script>
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
                    $(self).next().hide();
                }
            });
        });
    </script>
@endsection
