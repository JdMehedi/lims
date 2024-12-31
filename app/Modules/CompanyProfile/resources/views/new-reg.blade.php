@extends('layouts.admin')

@section('header-resources')

    <link rel="stylesheet" href="{{ asset("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}" />
    <link rel="stylesheet" href="{{ mix("assets/plugins/select2/css/select2.min.css") }}">
    {{--Step css--}}
    <link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
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
        .wizard > .actions {
            top: -15px;
        }
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
            z-index: 5;
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
    </style>

@endsection

@section('content')

    {{--Machinary locally collected modal--}}
    <div id="machine_local_collected_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                    <h4 id="local_machine_modal_head" class="modal-title" style="color: #452A73; font-size: 14px">{!!trans('CompanyProfile::messages.machinery_information')!!}</h4>
                    <h4 id="local_machineCollect_modal_head" class="modal-title" style="font-size: 16px" hidden>{!!trans('CompanyProfile::messages.imported')!!}</h4>
                </div>
                {!! Form::open(array('url' => '','method' => 'post', 'class' => 'form-horizontal', 'id' => '', 'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                <div class="modal-body">

                    {{--Choose option--}}
                    <div id="local_machine_choose_input" class="text-center">
                        <button id="local_machine_manual_input" type="button" class="btn" style="color: white; background: #2A8D46; width: 100px;">
                            <i class="fa fa-pencil"></i> {!!trans('CompanyProfile::messages.manually')!!}
                        </button>
                        <br>
                        {!!trans('CompanyProfile::messages.or')!!}
                        <br>
                        <button id="local_machine_file_upload" type="button" class="btn" style="color: white; background: #DFA40D; width: 100px;">
                            <i class="fa fa-file-excel-o"></i> {!!trans('CompanyProfile::messages.browse')!!}
                        </button>
                    </div>

                    {{--Add table--}}
                    <table id="local_machine_manual_input_table"
                           class="table table-bordered dt-responsive"
                           cellspacing="0" width="100%" hidden>
                        <thead>
                        <tr style="background: #F7F7F7;">
                            <th class="text-center">{!!trans('CompanyProfile::messages.machinery_name')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.n_number')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.price_taka')!!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>
                                {!! Form::text('local_machinery_name', '', ['class' => 'form-control input-md', 'placeholder'=> trans('CompanyProfile::messages.machinery_name'), 'id'=> 'local_machinery_name']) !!}
                                {!! $errors->first('local_machinery_name','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::text('local_machinery_number', '', ['class' => 'form-control input-md onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'local_machinery_number']) !!}
                                {!! $errors->first('local_machinery_number','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::text('local_machinery_price', '', ['class' => 'form-control input-md onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'local_machinery_price']) !!}
                                {!! $errors->first('local_machinery_price','<span class="help-block">:message</span>') !!}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div id="local_machine_file_upload_div" hidden>
                        <div class="row text-center" style="margin-bottom: 15px">
                            <a href="https://drive.google.com/file/d/1BtC_WVlsHk9Bw4zddSaDFi1xMT_QAKnH/view" style="font-size: 16px;"><i class="fa fa-file-excel-o"></i> নমুনা ফাইল</a>
                        </div>
                        <div class="sign_div" style="margin: 0 10px">

                            <div style="text-align: center;" >
                                <div class="signature-upload">
                                    <div class="text-center">
                                        <i class="fa fa-3x fa-file-excel-o" style="color: #258DFF"></i>
                                        <p style="font-size: 16px;">
                                            Drop your excel file scan copy here or <strong style="color: #259BFF">Browse</strong>
                                        </p>
                                        <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.xlsx/ .csv .xls | Maximum 5 MB]
                                                                </span>
                                    </div>
                                </div>
                                <input accept="image/*" type="file" name="file_upload"
                                       id="file_upload" class="signature-upload-input"
                                       onchange="handleLocalFileUpload()">
                            </div>
                        </div>
                    </div>

                    <table id="local_machine_details_table"
                           class="table table-bordered dt-responsive"
                           cellspacing="0" width="100%" hidden>
                        <thead>
                        <tr style="background: #F7F7F7;">
                            <th class="text-center">{!!trans('CompanyProfile::messages.number')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.machinery_source')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.machinery_name')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.n_number')!!}</th>
                            <th class="text-center">{!!trans('CompanyProfile::messages.price_taka')!!}</th>
                            <th class="text-center">#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>
                                ১
                            </td>
                            <td>
                                স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য
                            </td>
                            <td>
                                Swing Machine
                            </td>
                            <td>
                                ১২
                            </td>
                            <td>
                                ২০০০০০
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                ২
                            </td>
                            <td>
                                স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য
                            </td>
                            <td>
                                Derive Engine
                            </td>
                            <td>
                                ৪
                            </td>
                            <td>
                                ১০০০০০
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                ৩
                            </td>
                            <td>
                                স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য
                            </td>
                            <td>
                                Carpet car
                            </td>
                            <td>
                                ২
                            </td>
                            <td>
                                ৩০০০০০
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                            </td>
                        </tr>
                        <tr >
                            <td class="text-right" colspan="3">
                                {!!trans('CompanyProfile::messages.locally_collected_total')!!}
                            </td>
                            <td class="text-center">
                                ১৬
                            </td>
                            <td class="text-center">
                                ৩০০০০০
                            </td>
                        </tr>
                        <tr >
                            <td class="text-right" colspan="3">
                                {!!trans('CompanyProfile::messages.imported_total')!!}
                            </td>
                            <td class="text-center">
                                ২
                            </td>
                            <td class="text-center">
                                ৩০০০০০
                            </td>
                        </tr>
                        <tr >
                            <td class="text-right" colspan="3">
                                {!!trans('CompanyProfile::messages.grand_total')!!}
                            </td>
                            <td class="text-center">
                                ১৮
                            </td>
                            <td class="text-center">
                                ৬০০০০০
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm close_modal" data-dismiss="modal" style="float: left;">Close</button>

                    <div id="local_save_btn" style="float: right" hidden>
                        <button type="button" id="local_save_btn" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                        <button type="button" id="local_saveNew_btn" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save & New</button>
                    </div>
                    <div id="local_action_previous" style="float: right; margin-right: 8px" hidden>
                        <button type="button" id="local_previous_btn" class="btn btn-info btn-sm local_previous_btn">Previous</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 col-lg-12">

                {{--Industry registration--}}
                <div class="panel" style="border-radius: 10px;">
                    <div class="panel-heading" style="padding: 10px 15px"><p style="font-size: 32px; font-weight: 400">{!!trans('CompanyProfile::messages.industry_reg')!!}</p></div>
                    <div class="panel-body" style="padding: 0 15px">
                        {!! Form::open(array('url' => '','method' => 'post', 'class' =>
                        'form-horizontal', 'id' => 'application_form',
                        'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

                        <h3>{!!trans('CompanyProfile::messages.general_information')!!}</h3>
                        <br>
                        <fieldset>
                            {{--General Info--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.general_information')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">
                                                    {!! Form::text('company_name_bangla', '', ['placeholder' => trans("CompanyProfile::messages.write_company_name_bangla"),
                                                   'class' => 'form-control required input-md','id'=>'company_name_bangla']) !!}
                                                    {!! $errors->first('company_name_bangla','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_name_english', trans('CompanyProfile::messages.company_name_english'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_name_english') ? 'has-error': ''}}">
                                                    {!! Form::text('company_name_english','', ['placeholder' => trans("CompanyProfile::messages.write_company_name_english"),
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
                                                    {!! Form::text('project_name','', ['placeholder' => trans("CompanyProfile::messages.project_name"),
                                                   'class' => 'form-control input-md','id'=>'project_name']) !!}
                                                    {!! $errors->first('project_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('reg_type_id', trans('CompanyProfile::messages.reg_type'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('reg_type_id') ? 'has-error': ''}}">
                                                    {!! Form::select('reg_type_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'reg_type_id']) !!}
                                                    {!! $errors->first('reg_type_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_type') ? 'has-error': ''}}">
                                                    {!! Form::select('company_type_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_type_id']) !!}
                                                    {!! $errors->first('company_type_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('investment_type_id', trans('CompanyProfile::messages.investment_type'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('investment_type_id') ? 'has-error': ''}}">
                                                    {!! Form::select('investment_type_id[]', [], '', ['class' =>'form-control input-md investing_country_id', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investment_type_id']) !!}
                                                    {!! $errors->first('investment_type_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('investing_country_id', trans('CompanyProfile::messages.investing_country'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('investing_country_id') ? 'has-error': ''}}">
                                                    {!! Form::select('investing_country_id[]', [], '', ['class' =>'form-control input-md investing_country_id', 'placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'investing_country_id']) !!}
                                                    {!! $errors->first('investing_country_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('total_investment', trans('CompanyProfile::messages.total_investment'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('total_investment') ? 'has-error': ''}}">
                                                    {!! Form::text('total_investment','', ['placeholder' => trans("CompanyProfile::messages.write_total_investment"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'total_investment']) !!}
                                                    {!! $errors->first('total_investment','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('industrial_class_id', trans('CompanyProfile::messages.industrial_class'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('industrial_class') ? 'has-error': ''}}">
                                                    {!! Form::select('industrial_class_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_class_id']) !!}
                                                    {!! $errors->first('industrial_class_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('industrial_field_id', trans('CompanyProfile::messages.industrial_field'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('industrial_field_id') ? 'has-error': ''}}">
                                                    {!! Form::select('industrial_field_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_field_id']) !!}
                                                    {!! $errors->first('industrial_field_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('industrial_sub_field_id', trans('CompanyProfile::messages.industrial_field'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('industrial_sub_field_id') ? 'has-error': ''}}">
                                                    {!! Form::select('industrial_sub_field_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'industrial_sub_field_id']) !!}
                                                    {!! $errors->first('industrial_sub_field_id','<span class="help-block">:message</span>') !!}
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
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_office_division_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),
    'id'=>'company_office_division_id', 'onchange'=>"getDistrictByDivisionId('company_office_division_id', this.value, 'company_office_district_id')"]) !!}
                                                    {!! $errors->first('company_office_division_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_office_district_id', trans('CompanyProfile::messages.district'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_office_district_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),
    'id'=>'company_office_district_id', 'onchange'=>"getThanaByDistrictId('company_office_district_id', this.value, 'company_office_thana_id')"]) !!}
                                                    {!! $errors->first('company_office_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_office_thana_id', trans('CompanyProfile::messages.thana_upajila'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_office_thana_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_office_thana_id']) !!}
                                                    {!! $errors->first('company_office_thana_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_office_postCode', trans('CompanyProfile::messages.post_code'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                    {!! Form::text('company_office_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_office_postCode']) !!}
                                                    {!! $errors->first('company_office_postCode','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('company_office_address', trans('CompanyProfile::messages.address'),
                                                ['class'=>'col-md-2 ']) !!}
                                                <div class="col-md-10 {{$errors->has('company_office_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                    {!! Form::text('company_office_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                                   'class' => 'form-control input-md','id'=>'company_office_address']) !!}
                                                    {!! $errors->first('company_office_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_office_email', trans('CompanyProfile::messages.email'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_email') ? 'has-error': ''}}">
                                                    {!! Form::text('company_office_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                                   'class' => 'form-control input-md email','id'=>'company_office_email']) !!}
                                                    {!! $errors->first('company_office_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_office_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_mobile') ? 'has-error': ''}}">
                                                    {!! Form::text('company_office_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_office_mobile']) !!}
                                                    {!! $errors->first('company_office_mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--Company factory address--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_factory_address')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('same_address',1,null, array('id'=>'same_address', 'class'=>'')) !!}
                                                প্রতিষ্ঠানের কার্যালয় একং কারখানার ঠিকানা একই হলে টিক দিন
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_division_id', trans('CompanyProfile::messages.division'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_factory_division_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_factory_division_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),
    'id'=>'company_factory_division_id', 'onchange'=>"getDistrictByDivisionId('company_factory_division_id', this.value, 'company_factory_district_id')"]) !!}
                                                    {!! $errors->first('company_factory_division_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_district_id', trans('CompanyProfile::messages.district'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_factory_district_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_factory_district_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),
    'id'=>'company_factory_district_id', 'onchange'=>"getThanaByDistrictId('company_factory_district_id', this.value, 'company_factory_thana_id')"]) !!}
                                                    {!! $errors->first('company_factory_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_thana_id', trans('CompanyProfile::messages.thana_upajila'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_factory_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::select('company_factory_thana_id', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"), 'id'=>'company_factory_thana_id']) !!}
                                                    {!! $errors->first('company_factory_thana_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_postCode', trans('CompanyProfile::messages.post_code'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_office_postCode') ? 'has-error': ''}}">
                                                    {!! Form::text('company_factory_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_factory_postCode']) !!}
                                                    {!! $errors->first('company_factory_postCode','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('company_factory_address', trans('CompanyProfile::messages.address'),
                                                ['class'=>'col-md-2 ']) !!}
                                                <div class="col-md-10 {{$errors->has('company_office_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                    {!! Form::text('company_factory_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                                   'class' => 'form-control input-md','id'=>'company_factory_address']) !!}
                                                    {!! $errors->first('company_factory_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_email', trans('CompanyProfile::messages.email'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_factory_email') ? 'has-error': ''}}">
                                                    {!! Form::text('company_factory_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                                   'class' => 'form-control input-md email','id'=>'company_factory_email']) !!}
                                                    {!! $errors->first('company_factory_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_factory_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_factory_mobile') ? 'has-error': ''}}">
                                                    {!! Form::text('company_factory_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_factory_mobile']) !!}
                                                    {!! $errors->first('company_factory_mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            {{--Company ceo--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.company_ceo')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_name', trans('CompanyProfile::messages.name'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_name') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_name','', ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                       'class' => 'form-control input-md','id'=>'company_ceo_name']) !!}
                                                    {!! $errors->first('company_ceo_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_fatherName', trans('CompanyProfile::messages.father_name'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_fatherName') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_fatherName','', ['placeholder' => trans("CompanyProfile::messages.write_father_name"),
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
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('ceo_nationality') ? 'has-error': ''}}">
                                                    {!! Form::select('company_ceo_nationality', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_ceo_nationality']) !!}
                                                    {!! $errors->first('company_ceo_nationality','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_nid', trans('CompanyProfile::messages.nid'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_nid','', ['placeholder' => trans("CompanyProfile::messages.write_nid"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_ceo_nid']) !!}
                                                    {!! $errors->first('company_ceo_nid','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('company_ceo_dob') ? 'has-error': ''}}">
                                                {!! Form::label('company_ceo_dob',trans('CompanyProfile::messages.dob'),['class'=>'col-md-5']) !!}
                                                <div class=" col-md-7">
                                                    <div id="ceoDP" class="ceoDP input-group date">
                                                        {!! Form::text('company_ceo_dob', '', ['class'=>'form-control', 'placeholder' => trans("CompanyProfile::messages.select"), 'id'=>'company_ceo_dob']) !!}
                                                        <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                        {!! $errors->first('company_ceo_dob','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_designation', trans('CompanyProfile::messages.designation'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_designation','', ['placeholder' => trans("CompanyProfile::messages.write_designation"),
                                                       'class' => 'form-control input-md','id'=>'company_ceo_designation']) !!}
                                                    {!! $errors->first('company_ceo_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_division', trans('CompanyProfile::messages.division'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_division') ? 'has-error': ''}}">
                                                    {!! Form::select('company_ceo_division', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),'id'=>'company_ceo_division']) !!}
                                                    {!! $errors->first('company_ceo_division','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_district', trans('CompanyProfile::messages.district'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_district') ? 'has-error': ''}}">
                                                    {!! Form::select('company_ceo_district', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),'id'=>'company_ceo_district']) !!}
                                                    {!! $errors->first('company_ceo_district','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_thana', trans('CompanyProfile::messages.thana_upajila'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_thana') ? 'has-error': ''}}">
                                                    {!! Form::select('company_ceo_thana', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_ceo_thana']) !!}
                                                    {!! $errors->first('company_ceo_thana','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_postCode', trans('CompanyProfile::messages.post_code'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_postCode') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_postCode','', ['placeholder' => trans("CompanyProfile::messages.write_post_code"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_ceo_postCode']) !!}
                                                    {!! $errors->first('company_ceo_postCode','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('company_ceo_address', trans('CompanyProfile::messages.address'),
                                                ['class'=>'col-md-2 ']) !!}
                                                <div class="col-md-10 {{$errors->has('company_ceo_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                    {!! Form::text('company_ceo_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                                   'class' => 'form-control input-md','id'=>'company_ceo_address']) !!}
                                                    {!! $errors->first('company_ceo_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_email', trans('CompanyProfile::messages.email'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                                   'class' => 'form-control input-md email','id'=>'company_ceo_email']) !!}
                                                    {!! $errors->first('company_ceo_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('company_ceo_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('company_ceo_mobile') ? 'has-error': ''}}">
                                                    {!! Form::text('company_ceo_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'company_ceo_mobile']) !!}
                                                    {!! $errors->first('company_ceo_mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Signature--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.ceo_signature')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('ceo_name', trans('CompanyProfile::messages.name'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('ceo_name') ? 'has-error': ''}}">
                                                    {!! Form::text('ceo_name','', ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                       'class' => 'form-control input-md','id'=>'ceo_name']) !!}
                                                    {!! $errors->first('ceo_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('ceo_designation', trans('CompanyProfile::messages.designation'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::text('ceo_designation','', ['placeholder' => trans("CompanyProfile::messages.designation"),
                                                       'class' => 'form-control input-md','id'=>'ceo_designation']) !!}
                                                    {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="col-md-7 col-centered">
                                        <div class="form-group">
                                            <div class="sign_div" style="margin: 0 10px">

                                                <div style="text-align: center;" >
                                                    <div class="signature-upload">
                                                        <div class="text-center">
                                                            <img src="/assets/images/pictures.png" alt="" >
                                                            <p style="font-size: 16px;">
                                                                আপনার স্ক্যান স্বাক্ষরটি এখানে ফেলে দিন বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>
                                                            </p>
                                                            <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.jpg/ .jpeg .png | Maximum 5 MB]
                                                                </span>
                                                        </div>
                                                    </div>
                                                    <input accept="image/*" type="file" name="signature_upload"
                                                           id="signature_upload" class="signature-upload-input"
                                                           onchange="handleSignatureUpload()">
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <span  style="color: #FF7272">প্রয়োজনীয় সকল কাগজপত্র এই স্বাক্ষরের মাধ্যমে স্বাক্ষরিত হবে</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Reg Office name--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.pref_reg_office')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('pref_reg_office', trans('CompanyProfile::messages.pref_reg_office'),
                                                ['class'=>'col-md-3 ']) !!}
                                                <div class="col-md-9 {{$errors->has('pref_reg_office') ? 'has-error': ''}}">
                                                    {!! Form::select('pref_reg_office', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'pref_reg_office']) !!}
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
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">ক. {!!trans('CompanyProfile::messages.company_main_works_info')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('company_main_works', trans('CompanyProfile::messages.company_main_works'),
                                                ['class'=>'col-md-2 required-star']) !!}
                                                <div class="col-md-10 {{$errors->has('company_main_works') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                    {!! Form::text('company_main_works','', ['placeholder' => trans("CompanyProfile::messages.company_main_works"),
                                                   'class' => 'form-control input-md','id'=>'company_main_works']) !!}
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
                                                    <div class="manufacture_starting_dateDP input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                        {!! Form::text('manufacture_starting_date', '', ['class'=>'form-control', 'placeholder' => 'mm/dd/yyyy', 'data-rule-maxlength'=>'40', 'id'=>'manufacture_starting_date']) !!}
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
                                                    <div class="project_deadlineDP input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                                                        {!! Form::text('project_deadline', '', ['class'=>'form-control', 'placeholder' => 'mm/dd/yyyy', 'data-rule-maxlength'=>'40', 'id'=>'project_deadline']) !!}
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
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">খ. {!!trans('CompanyProfile::messages.company_annual_production_capacity')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table id="annualProductionTable"
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.number')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.service_name')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.amount')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.price_lak_taka')!!}</th>
                                            <th class="text-center">#</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="annualProductionRow" data-number="1">
                                            <td class="text-center">
                                                ১
                                            </td>
                                            <td>
                                                {!! Form::select('service_name[]', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('service_amount[]', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.write_amount")]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('service_price[]', '', ['class' => 'form-control input-md text-center', 'placeholder' => trans('CompanyProfile::messages.price_lak_taka')]) !!}
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                   onclick="addTableRow('annualProductionTable', 'annualProductionRow');"><i
                                                            class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--Sell parcent--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">গ. {!!trans('CompanyProfile::messages.sell_parcent')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('local', trans('CompanyProfile::messages.local'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('local') ? 'has-error': ''}}">
                                                    {!! Form::text('local', '', ['placeholder' => '0%',
                                                   'class' => 'form-control input-md','id'=>'local']) !!}
                                                    {!! $errors->first('local','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('foreign', trans('CompanyProfile::messages.foreign'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('foreign') ? 'has-error': ''}}">
                                                    {!! Form::text('foreign','', ['placeholder' => '0%',
                                                   'class' => 'form-control input-md','id'=>'foreign']) !!}
                                                    {!! $errors->first('foreign','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Company manpower--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">ঘ. {!!trans('CompanyProfile::messages.company_annual_production_capacity')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table id=""
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center" colspan="3">{!!trans('CompanyProfile::messages.local_bangladeshi')!!}</th>
                                            <th class="text-center" colspan="3">{!!trans('CompanyProfile::messages.foreign')!!}</th>
                                            <th class="text-center" colspan="2">{!!trans('CompanyProfile::messages.total')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center" style="background: #F7F7F7">
                                            <td >{!!trans('CompanyProfile::messages.men')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.women')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.total')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.men')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.women')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.total')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.grand_total')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.rate')!!}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {!! Form::text('local_men_number', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'local_men_number']) !!}
                                                {!! $errors->first('local_men_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_women_number', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'local_women_number']) !!}
                                                {!! $errors->first('local_women_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_total', '', ['class' => 'form-control input-md text-center', 'disabled', 'placeholder'=> trans("CompanyProfile::messages.total"), 'id'=>'local_total']) !!}
                                                {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_men_number', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'foreign_men_number']) !!}
                                                {!! $errors->first('foreign_men_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_women_number', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.n_number"), 'id'=>'foreign_women_number']) !!}
                                                {!! $errors->first('foreign_women_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_total', '', ['class' => 'form-control input-md text-center', 'disabled', 'placeholder'=> trans("CompanyProfile::messages.total"), 'id'=>'foreign_total']) !!}
                                                {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('grand_total', '', ['class' => 'form-control input-md text-center', 'disabled', 'placeholder'=> trans("CompanyProfile::messages.grand_total"), 'id'=>'grand_total']) !!}
                                                {!! $errors->first('grand_total','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('ratio', '', ['class' => 'form-control input-md text-center', 'disabled', 'placeholder'=> trans("CompanyProfile::messages.rate"), 'id'=>'ratio']) !!}
                                                {!! $errors->first('ratio','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--Necessary service description--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">ঙ. {!!trans('CompanyProfile::messages.necessary_services_details')!!}</p></div>

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
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.electricity')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                            <label class="radio control-label" for="electricity1" style="margin-right: 15px">
                                                                {!! Form::radio('electricity', 'yes', false, ['class'=>'', 'id' => 'electricity1']) !!}
                                                                {!! trans('CompanyProfile::messages.yes') !!}
                                                            </label>


                                                            <label class="radio control-label" for="electricity2" style="margin-left: 15px">
                                                                {!! Form::radio('electricity', 'no', false, ['class'=>'', 'id' => 'electricity2']) !!}
                                                                {!! trans('CompanyProfile::messages.no') !!}
                                                            </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('electricity_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'electricity_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('electricity_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("electricity_distance_select", [], null, ["id"=>"electricity_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('electricity_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.gas')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                        <label class="radio control-label" for="gas1" style="margin-right: 15px">
                                                            {!! Form::radio('gas', 'yes', false, ['class'=>'', 'id' => 'gas1']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>


                                                        <label class="radio control-label" for="gas2" style="margin-left: 15px">
                                                            {!! Form::radio('gas', 'no', false, ['class'=>'', 'id' => 'gas2']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('gas_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'gas_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('gas_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("gas_distance_select", [], null, ["id"=>"gas_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('gas_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.telephone')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                        <label class="radio control-label" for="telephone1" style="margin-right: 15px">
                                                            {!! Form::radio('telephone', 'yes', false, ['class'=>'', 'id' => 'telephone1']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>


                                                        <label class="radio control-label" for="telephone2" style="margin-left: 15px">
                                                            {!! Form::radio('telephone', 'no', false, ['class'=>'', 'id' => 'telephone1']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('telephone_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'telephone_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('telephone_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("telephone_distance_select", [], null, ["id"=>"telephone_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('telephone_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.road')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                        <label class="radio control-label" for="road1" style="margin-right: 15px">
                                                            {!! Form::radio('road', 'yes', false, ['class'=>'', 'id' => 'road1']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>


                                                        <label class="radio control-label" for="road2" style="margin-left: 15px">
                                                            {!! Form::radio('road', 'no', false, ['class'=>'', 'id' => 'road1']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('road_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'road_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('road_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("road_distance_select", [], null, ["id"=>"road_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('road_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.water')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                        <label class="radio control-label" for="water1" style="margin-right: 15px">
                                                            {!! Form::radio('water', 'yes', false, ['class'=>'', 'id' => 'water1']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>


                                                        <label class="radio control-label" for="water2" style="margin-left: 15px">
                                                            {!! Form::radio('water', 'no', false, ['class'=>'', 'id' => 'water1']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('water_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'water_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('water_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("water_distance_select", [], null, ["id"=>"water_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('water_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="helpTextCom" id="">{!!trans('CompanyProfile::messages.sewerage')!!}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">

                                                        <label class="radio control-label" for="sewerage1" style="margin-right: 15px">
                                                            {!! Form::radio('sewerage', 'yes', false, ['class'=>'', 'id' => 'sewerage1']) !!}
                                                            {!! trans('CompanyProfile::messages.yes') !!}
                                                        </label>


                                                        <label class="radio control-label" for="sewerage2" style="margin-left: 15px">
                                                            {!! Form::radio('sewerage', 'no', false, ['class'=>'', 'id' => 'sewerage1']) !!}
                                                            {!! trans('CompanyProfile::messages.no') !!}
                                                        </label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('sewerage_distance', null, ['class' => 'form-control input-md onlyNumber text-center','id'=>'sewerage_distance',
                                                                'placeholder' => trans('CompanyProfile::messages.distance')
                                                                ]) !!}
                                                                {!! $errors->first('sewerage_distance','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("sewerage_distance_select", [], null, ["id"=>"sewerage_distance_select", "class" => "form-control input-md", 'placeholder' => trans('CompanyProfile::messages.select')]) !!}
                                                                {!! $errors->first('sewerage_distance_select','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{--Investment--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">চ. {!!trans('CompanyProfile::messages.investment')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" cellspacing="0"
                                               width="100%">
                                            <thead style="background-color: #F7F7F7">
                                            <tr>
                                                <th class="text-center" colspan="3">{!!trans('CompanyProfile::messages.fixed_investment')!!}</th>
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
                                                                {!! Form::number('local_land_ivst', Session::get('brInfo.local_land_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control total_investment_item input-md number','id'=>'local_land_ivst',
                                                                 'onblur' => 'CalculateTotalInvestmentTk()'
                                                                ]) !!}
                                                                {!! $errors->first('local_land_ivst','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("local_land_ivst_ccy", [], (Session::get('brInfo.local_land_ivst_ccy') ? Session::get('brInfo.local_land_ivst_ccy') : 114), ["id"=>"local_land_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
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
                                                                {!! Form::number('local_building_ivst', Session::get('brInfo.local_building_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md total_investment_item number','id'=>'local_building_ivst',
                                                                 'onblur' => 'CalculateTotalInvestmentTk()']) !!}
                                                                {!! $errors->first('local_building_ivst','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("local_building_ivst_ccy", [], (Session::get('brInfo.local_building_ivst_ccy') ? Session::get('brInfo.local_building_ivst_ccy') : 114), ["id"=>"local_building_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                                {!! $errors->first('local_building_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="position: relative;">
                                                                <span class="required-star helpTextCom"
                                                                      id="investment_machinery_equp_label">{!!trans('CompanyProfile::messages.machinery')!!}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td style="width:75%;">
                                                                {!! Form::number('local_machinery_ivst', Session::get('brInfo.local_machinery_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md number total_investment_item','id'=>'local_machinery_ivst',
                                                                'onblur' => 'CalculateTotalInvestmentTk()']) !!}
                                                                {!! $errors->first('local_machinery_ivst','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("local_machinery_ivst_ccy", [], (Session::get('brInfo.local_machinery_ivst_ccy') ? Session::get('brInfo.local_machinery_ivst_ccy') : 114), ["id"=>"local_machinery_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
                                                                {!! $errors->first('local_machinery_ivst_ccy','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr style="background-color: #F7F7F7">
                                                <td>
                                                    <div style="position: relative;">
                                                        <span class="helpTextCom" id="investment_others_label">{!!trans('CompanyProfile::messages.others')!!}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td style="width:75%;">
                                                                {!! Form::number('local_others_ivst', Session::get('brInfo.local_others_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md number total_investment_item','id'=>'local_others_ivst',
                                                                'onblur' => 'CalculateTotalInvestmentTk()']) !!}
                                                                {!! $errors->first('local_others_ivst','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("local_others_ivst_ccy", [], (Session::get('brInfo.local_others_ivst_ccy') ? Session::get('brInfo.local_others_ivst_ccy') : 114), ["id"=>"local_others_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
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
                                                                {!! Form::number('local_wc_ivst', Session::get('brInfo.local_wc_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md number total_investment_item','id'=>'local_wc_ivst',
                                                                'onblur' => 'CalculateTotalInvestmentTk()']) !!}
                                                                {!! $errors->first('local_wc_ivst','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::select("local_wc_ivst_ccy", [], (Session::get('brInfo.local_wc_ivst_ccy') ? Session::get('brInfo.local_wc_ivst_ccy') : 114), ["id"=>"local_wc_ivst_ccy", "class" => "form-control input-md usd-def"]) !!}
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
                                                                      id="investment_total_invst_mi_label">{!!trans('CompanyProfile::messages.same_amount_taka_doller')!!}</span>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    {!! Form::number('total_fixed_ivst_million', Session::get('brInfo.total_fixed_ivst_million'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative total_fixed_ivst_million','id'=>'total_fixed_ivst_million','readonly']) !!}
                                                    {!! $errors->first('total_fixed_ivst_million','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="position: relative;">
                                                                <span class="helpTextCom"
                                                                      id="investment_total_invst_bd_label">{!!trans('CompanyProfile::messages.grandtotal_lak_bdt')!!}</span>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    {!! Form::number('total_fixed_ivst', Session::get('brInfo.total_fixed_ivst'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative total_invt_bdt','id'=>'total_invt_bdt','readonly']) !!}
                                                    {!! $errors->first('total_fixed_ivst','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F7F7F7">
                                                <td>
                                                    <div style="position: relative;">
                                                                <span class="helpTextCom required-star"
                                                                      id="investment_total_invst_usd_label">{!!trans('CompanyProfile::messages.dollar_rate')!!}</span>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    {!! Form::number('usd_exchange_rate', Session::get('brInfo.usd_exchange_rate'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative','id'=>'usd_exchange_rate']) !!}
                                                    {!! $errors->first('usd_exchange_rate','<span class="help-block">:message</span>') !!}
                                                    <span class="help-text">Exchange Rate Ref: <a
                                                                href="https://www.bangladesh-bank.org/econdata/exchangerate.php"
                                                                target="_blank">Bangladesh Bank</a>. Please Enter Today's Exchange Rate</span>
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
                                                                {!! Form::text('total_fee', Session::get('brInfo.total_fee'), ['class' => 'form-control input-md number', 'id'=>'total_fee', 'readonly']) !!}
                                                            </td>
                                                            <td>
                                                                <a type="button" class="btn btn-md btn-info"
                                                                   data-toggle="modal" data-target="#myModal">Govt.
                                                                    Fees Calculator</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <table id=""
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center" colspan="4">{!!trans('CompanyProfile::messages.investment_source')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center" style="background: #FFFFFF">
                                            <td >{!!trans('CompanyProfile::messages.source')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.taka')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.dollar')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.loan_org_country')!!}</td>
                                        </tr>
                                        <tr style="background: #F9F9F9">
                                            <td>
                                                {!!trans('CompanyProfile::messages.ceo_same_invest')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_taka_loan', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=> 'ceo_taka_loan']) !!}
                                                {!! $errors->first('ceo_taka_loan','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_dollar_loan', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=>'ceo_dollar_loan']) !!}
                                                {!! $errors->first('ceo_dollar_loan','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('ceo_loan_country', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'ceo_loan_country']) !!}
                                                {!! $errors->first('ceo_loan_country','<span class="help-block">:message</span>') !!}
                                            </td>

                                        </tr>
                                        <tr style="background: #FFFFFF">
                                            <td>
                                                {!!trans('CompanyProfile::messages.local_loan')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_taka', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=> 'local_loan_taka']) !!}
                                                {!! $errors->first('local_loan_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_dollar', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=>'local_loan_dollar']) !!}
                                                {!! $errors->first('local_loan_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_loan_country', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'local_loan_country']) !!}
                                                {!! $errors->first('local_loan_country','<span class="help-block">:message</span>') !!}
                                            </td>

                                        </tr>
                                        <tr style="background: #F9F9F9">
                                            <td>
                                                {!!trans('CompanyProfile::messages.foreign_loan')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_taka', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=> 'foreign_loan_taka']) !!}
                                                {!! $errors->first('foreign_loan_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_dollar', '', ['class' => 'form-control input-md text-center', 'placeholder'=> '0', 'id'=>'foreign_loan_dollar']) !!}
                                                {!! $errors->first('foreign_loan_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('foreign_loan_country', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.loan_org_country"), 'id'=>'foreign_loan_country']) !!}
                                                {!! $errors->first('foreign_loan_country','<span class="help-block">:message</span>') !!}
                                            </td>

                                        </tr>
                                        <tr style="background: #FFFFFF">
                                            <td class="text-right">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td>
                                                {!! Form::text('grand_total_taka', '', ['class' => 'form-control input-md', 'disabled', 'placeholder'=> '0', 'id'=> 'grand_total_taka']) !!}
                                                {!! $errors->first('grand_total_taka','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('grand_total_dollar', '', ['class' => 'form-control input-md', 'disabled', 'placeholder'=> '0', 'id'=>'grand_total_dollar']) !!}
                                                {!! $errors->first('grand_total_dollar','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="panel">
                                        <div class="panel-heading" style="background-color: #F7F7F7; border: 1px solid #DDDDDD; border-bottom: none;">
                                            <p class="text-center" style="font-size: 16px; margin-top: 5px">{!!trans('CompanyProfile::messages.country_wise_loan_source')!!}</p>
                                        </div>
                                        <div class="panel-body" style="padding: 0">
                                            <table id="loanSourceTable"
                                                   class="table table-bordered dt-responsive"
                                                   cellspacing="0" width="100%">
                                                <thead style="background-color: #F7F7F7">
                                                {{--<tr>--}}
                                                {{--<th class="text-center" colspan="5">{!!trans('CompanyProfile::messages.country_wise_loan_source')!!}</th>--}}
                                                {{--</tr>--}}
                                                <tr style="font-size: 16px;">
                                                    <th style="font-weight: normal;" class="text-center">{!!trans('CompanyProfile::messages.country_name')!!}</th>
                                                    <th style="font-weight: normal;" class="text-center">{!!trans('CompanyProfile::messages.org_name')!!}</th>
                                                    <th style="font-weight: normal;" class="text-center">{!!trans('CompanyProfile::messages.loan_amount')!!}</th>
                                                    <th style="font-weight: normal;" class="text-center">{!!trans('CompanyProfile::messages.loan_taking_date')!!}</th>
                                                    <th style="font-weight: normal;" class="text-center">#</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id="loanSourceRow" data-number="1">
                                                    <td class="text-center">
                                                        {!! Form::select('country_name_id[]', [], '', ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('organization_name[]', '', ['class' => 'form-control input-md text-center', 'placeholder'=> trans("CompanyProfile::messages.org_name")]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('loan_amount[]', '', ['class' => 'form-control input-md text-center onlyNumber', 'placeholder' => trans('CompanyProfile::messages.loan_amount')]) !!}
                                                    </td>
                                                    <td>
                                                        <div class="datepicker input-group date">
                                                            {!! Form::text('loan_taking_date[]', '', ['class'=>'form-control', 'placeholder' => 'mm/dd/yyyy', 'id'=>'']) !!}
                                                            <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                                            {!! $errors->first('loan_taking_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-success addTableRows" title="Add more"
                                                           onclick="addTableRow('loanSourceTable', 'loanSourceRow');"><i
                                                                    class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </fieldset>

                        <h3>{!!trans('CompanyProfile::messages.machinery_information')!!}</h3>
                        <fieldset>
                            {{--Machinary information--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.machinery_information')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table id=""
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="text-center" colspan="5">{!!trans('CompanyProfile::messages.locally_collected')!!}
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#machine_local_collected_modal" style="float: right;">{!!trans('CompanyProfile::messages.add_more')!!}</button>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center" style="background: #F7F7F7">
                                            <td >{!!trans('CompanyProfile::messages.number')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.machinery_name')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.n_number')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.price_taka')!!}</td>
                                            <td >#</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>
                                                ১
                                            </td>
                                            <td>
                                                Swing Machine
                                            </td>
                                            <td>
                                                ১২
                                            </td>
                                            <td>
                                                ২০০০০০
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                                            </td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>
                                                ২
                                            </td>
                                            <td>
                                                Derive Engine
                                            </td>
                                            <td>
                                                ৪
                                            </td>
                                            <td>
                                                ১০০০০০
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="3">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td class="text-center">
                                                ৩০০০০০
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table id=""
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="text-center" colspan="5">{!!trans('CompanyProfile::messages.imported')!!}
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#machine_local_collected_modal" style="float: right;">{!!trans('CompanyProfile::messages.add_more')!!}</button>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center" style="background: #F7F7F7">
                                            <td >{!!trans('CompanyProfile::messages.number')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.machinery_name')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.n_number')!!}</td>
                                            <td >{!!trans('CompanyProfile::messages.price_taka')!!}</td>
                                            <td >#</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>
                                                ১
                                            </td>
                                            <td>
                                                Swing Machine
                                            </td>
                                            <td>
                                                ১২
                                            </td>
                                            <td>
                                                ২০০০০০
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                                            </td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>
                                                ২
                                            </td>
                                            <td>
                                                Derive Engine
                                            </td>
                                            <td>
                                                ৪
                                            </td>
                                            <td>
                                                ১০০০০০
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-edit"> Edit</i></button>
                                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"> Delete</i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" colspan="3">
                                                {!!trans('CompanyProfile::messages.grand_total')!!}
                                            </td>
                                            <td class="text-center">
                                                ৩০০০০০
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--Raw material details--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.raw_material_package_details')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
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
                                                {!! Form::text('local_raw_material_number', '', ['class' => 'form-control input-md text-center onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'local_raw_material_number']) !!}
                                                {!! $errors->first('local_raw_material_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('local_raw_material_price', '', ['class' => 'form-control input-md text-center onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'local_raw_material_price']) !!}
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
                                                {!! Form::text('import_raw_material_number', '', ['class' => 'form-control input-md text-center onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'import_raw_material_number']) !!}
                                                {!! $errors->first('import_raw_material_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('import_raw_material_price', '', ['class' => 'form-control input-md text-center onlyNumber', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'import_raw_material_price']) !!}
                                                {!! $errors->first('import_raw_material_price','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ৩
                                            </td>
                                            <td class="text-center">
                                                সর্বমোট
                                            </td>
                                            <td>
                                                {!! Form::text('raw_material_total_number', '', ['class' => 'form-control input-md text-center onlyNumber', 'disabled', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'raw_material_total_number']) !!}
                                                {!! $errors->first('raw_material_total_number','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('raw_material_total_price', '', ['class' => 'form-control input-md text-center onlyNumber', 'disabled', 'placeholder'=> trans('CompanyProfile::messages.taka'), 'id'=> 'raw_material_total_price']) !!}
                                                {!! $errors->first('raw_material_total_price','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-primary pull-right" style="margin-bottom: 5px" id="addMoreDirector" onclick="addMoreDirector()">Add More Director</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table class="table table-bordered">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.number')!!}</th>
                                            <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.name')!!}</th>
                                            <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.designation')!!}</th>
                                            <th class="text-center" scope="col">NID/TIN/Passport</th>
                                            <th class="text-center" scope="col">{!!trans('CompanyProfile::messages.number_nationality')!!}</th>
                                            <th class="text-center" scope="col">#</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="text-center">
                                            <td>১</td>
                                            <td>মোহাম্মদ সিরাজুল ইসলাম</td>
                                            <td>প্রোপ্রাইটর</td>
                                            <td>১৯৮৯৭৬৭৮৭৬৭৬৭</td>
                                            <td>বাংলাদেশী</td>
                                            <td><button class="btn btn-primary btn-sm" style="margin-right: 7px"><i class="fa fa-edit"></i> Edit</button><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>

                        <h3>{!!trans('CompanyProfile::messages.attachments')!!}</h3>
                        <fieldset>
                            {{--Applicant info--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.approved_applicant_info')!!}</p></div>

                                <div class="panel-body" style="padding: 15px 25px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_name', trans('CompanyProfile::messages.name'),
                                                ['class'=>'col-md-5 ']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_name','', ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                       'class' => 'form-control input-md','id'=>'applicant_name']) !!}
                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_designation', trans('CompanyProfile::messages.designation'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_designation') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_designation','', ['placeholder' => trans("CompanyProfile::messages.write_designation"),
                                                       'class' => 'form-control input-md','id'=>'applicant_designation']) !!}
                                                    {!! $errors->first('applicant_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('applicant_address', trans('CompanyProfile::messages.address'),
                                                ['class'=>'col-md-2']) !!}
                                                <div class="col-md-10 {{$errors->has('applicant_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">
                                                    {!! Form::text('applicant_address','', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                                   'class' => 'form-control input-md','id'=>'applicant_address']) !!}
                                                    {!! $errors->first('applicant_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_mobile') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_mobile','', ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                                   'class' => 'form-control input-md onlyNumber','id'=>'applicant_mobile']) !!}
                                                    {!! $errors->first('applicant_mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_email', trans('CompanyProfile::messages.email'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_email') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_email','', ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                                   'class' => 'form-control input-md email','id'=>'applicant_email']) !!}
                                                    {!! $errors->first('applicant_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('approved_permission_letter', trans('CompanyProfile::messages.approved_permission_letter'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('approved_permission_letter') ? 'has-error': ''}}">
                                                    {!! Form::file('approved_permission_letter', ['class'=>'form-control input-md', 'id' => 'approved_permission_letter','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                                    {!! $errors->first('approved_permission_letter','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_image', trans('CompanyProfile::messages.image'),
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_image') ? 'has-error': ''}}">
                                                    {!! Form::file('applicant_image', ['class'=>'form-control input-md', 'id' => 'applicant_image','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                                    {!! $errors->first('applicant_image','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Necessary attachment--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.necessary_attachment')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table id=""
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead style="background-color: #F7F7F7">
                                        <tr>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.number')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.attachments_name')!!}</th>
                                            <th class="text-center">{!!trans('CompanyProfile::messages.attachments')!!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr >
                                            <td class="text-center">
                                                ১
                                            </td>
                                            <td>
                                                {!!trans('CompanyProfile::messages.factory_address_tl')!!}
                                            </td>
                                            <td>
                                                {!! Form::file('trade_license_img', ['class'=>'form-control input-md', 'id' => 'trade_license_img','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                            </td>
                                        </tr>
                                        <tr >
                                            <td class="text-center">
                                                ২
                                            </td>
                                            <td>
                                                {!!trans('CompanyProfile::messages.company_name_tin')!!}
                                            </td>
                                            <td>
                                                {!! Form::file('tin_img', ['class'=>'form-control input-md', 'id' => 'tin_img','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                            </td>
                                        </tr>
                                        <tr >
                                            <td class="text-center">
                                                ৩
                                            </td>
                                            <td>
                                                {!!trans('CompanyProfile::messages.rent_bond')!!}
                                            </td>
                                            <td>
                                                {!! Form::file('rent_bond_img', ['class'=>'form-control input-md', 'id' => 'rent_bond_img','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                            </td>
                                        </tr>
                                        <tr >
                                            <td class="text-center">
                                                ৪
                                            </td>
                                            <td>
                                                {!!trans('CompanyProfile::messages.agreement_letter')!!}
                                            </td>
                                            <td>
                                                {!! Form::file('agreement_letter_img', ['class'=>'form-control input-md', 'id' => 'agreement_letter_img','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                            </td>
                                        </tr>
                                        <tr >
                                            <td class="text-center">
                                                ৫
                                            </td>
                                            <td>
                                                {!!trans('CompanyProfile::messages.other_papers')!!}
                                            </td>
                                            <td>
                                                {!! Form::file('other_papers_img', ['class'=>'form-control input-md', 'id' => 'other_papers_img','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <span><p>সংযুক্তির নির্দেশিকা- সার্ভিসের নাম ও প্রতিষ্ঠানের ধরণ নির্ধারণের পর সংযুক্তির তালিকা প্রদর্শিত হবে।</p></span>
                                </div>
                            </div>

                            {{--Announcement--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.announcement')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
                                    <p style="color: #452A73;">
                                        বাংলাদেশ ক্ষুদ্র ও কুটির শিল্প করপোরেশন (বিসিক) এর ওয়ান স্টপ সার্ভিস সিস্টেমের মাধ্যমে জমাকৃত আবেদনপত্রে কোন ধরণের অসঙ্গতি পরিলক্ষিত হলে গণপ্রজাতন্ত্রী বাংলাদেশ সরকারের আইসিটি আইনের আওতায় দায়বন্ধ থাকবেন।
                                    </p>
                                    <br>
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('agreement',1,null, array('id'=>'agreement', 'class'=>'')) !!}
                                            আমি এই মর্মে ঘোষণা করছি যে উপরে বর্ণিত যাবতীয় তথ্য সঠিক আছে। কোন তথ্য গোপন করা হয় নাই। কোন তথ্য সঠিক পাওয়া না গেলে কর্তৃপক্ষ শিল্প প্রতিষ্ঠানটির নিবন্ধন বাতিল করতে পারবে এবং যে কোন প্রকার প্রশাসনিক ও আইনানুগ ব্যবস্থা গ্রহণ করতে পারবে। আমি আরো ঘোষণা করছি যে, কর্তৃপক্ষ কর্তৃক সরেজমিনে পরিদর্শনকালে সকল প্রকার সহযোগিতা প্রদান করতে বাধ্য থাকবো।
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3>{!!trans('CompanyProfile::messages.payment_and_submit')!!}</h3>
                        <fieldset>
                            {{--Announcement--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" style="border-bottom: none; padding: 7px 25px;"><p style="margin: 0; color: #452A73; font-size: 18px; font-weight: 400">{!!trans('CompanyProfile::messages.payment_summary')!!}</p></div>
                                <div class="panel-body" style="padding: 15px 25px;">
                                    <table class="table table-striped table-bordered" style="width: 50%; margin: auto">
                                        <thead>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{!!trans('CompanyProfile::messages.reg_fee')!!}</td>
                                            <td class="text-center">১০০ টাকা</td>
                                        </tr>
                                        <tr>
                                            <td>VAT</td>
                                            <td class="text-center">৪  টাকা</td>
                                        </tr>
                                        <tr>
                                            <td>TAX</td>
                                            <td class="text-center">৫  টাকা</td>
                                        </tr>
                                        <tr>
                                            <td>Service Fee</td>
                                            <td class="text-center">৫  টাকা</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right">{!!trans('CompanyProfile::messages.total')!!}</td>
                                            <td class="text-center">১১৪ টাকা</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>

                        {!! Form::close() !!}

                    </div>

                </div>{{--Industry registration--}}


            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>
    <script src="{{ mix("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>
    <script>

        $(document).ready(function () {
            var form = $("#application_form").show();

            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex)
                    {
                        return true;
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
                    // Used to skip the "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                    {
                        form.steps("next");
                    }
                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        form.steps("previous");
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
                }
            }).validate({
                errorPlacement: function errorPlacement(error, element) { element.before(error); },
                rules: {
                    confirm: {
                        equalTo: "#password-2"
                    }
                }
            });
        });

        // Upload csv file
        function handleLocalFileUpload(){
            $('#local_machine_file_upload_div').hide()
            $('#local_action_previous').hide()
            $('#local_machine_details_table').show()
            $('#local_save_btn').show()
        }

        $(document).ready(function () {

            // Local machine info modal script start
            $('#local_machine_manual_input').on('click', function(){
                $('#local_machine_modal_head').hide()
                $('#local_machine_choose_input').hide()
                $('#local_machineCollect_modal_head').show()
                $('#local_machine_manual_input_table').show()
                $('#local_save_btn').show()
                $('#local_action_previous').show()
            })

            $('.local_previous_btn').on('click', function(){
                $('#local_machine_manual_input_table').find('input:text').val('');
                $('#local_machineCollect_modal_head').hide()
                $('#local_machine_manual_input_table').hide()
                $('#local_machine_file_upload_div').hide()
                $('#local_save_btn').hide()
                $('#local_action_previous').hide()
                $('#local_machine_modal_head').show()
                $('#local_machine_choose_input').show()
            })

            $('#local_machine_file_upload').on('click', function(){
                $('#local_machine_choose_input').hide()
                $('#local_machine_file_upload_div').show()
                $('#local_action_previous').show()
            })


            $('.close_modal').on('click', function(){
                $('#local_machine_manual_input_table').find('input:text').val('')
                $('#local_machine_file_upload_div').find('input:file').val('');
                $('#local_machine_manual_input_table').hide()
                $('#local_machine_details_table').hide()
                $('#local_save_btn').hide()
                $('#local_action_previous').hide()
                $('#local_machine_file_upload_div').hide()
                $('#local_machineCollect_modal_head').hide()
                $('#local_machine_modal_head').show()
                $('#local_machine_choose_input').show()
            })
            // Local machine info modal script end


            var today = new Date();
            var yyyy = today.getFullYear();
            $('#ceoDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $('.manufacture_starting_dateDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $('.project_deadlineDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                maxDate: 'now',
                minDate: '01/01/1905'
            });

            // Add table Row script
            function addTableRow(tableID, template_row_id) {

                let i;
// Copy the template row (first row) of table and reset the ID and Styling
                const new_row = document.getElementById(template_row_id).cloneNode(true);
                new_row.id = "";
                new_row.style.display = "";

                // Get the total row number, and last row number of table
                let current_total_row = $('#' + tableID).find('tbody tr').length;
                let final_total_row = current_total_row + 1;


                // Generate an ID of the new Row, set the row id and append the new row into table
                let last_row_number = $('#' + tableID).find('tbody tr').last().attr('data-number');
                if (last_row_number != '' && typeof last_row_number !== "undefined") {
                    last_row_number = parseInt(last_row_number) + 1;
                } else {
                    last_row_number = Math.floor(Math.random() * 101);
                }

                const new_row_id = 'rowCount' + tableID + last_row_number;
                new_row.id = new_row_id;
                $("#" + tableID).append(new_row);

                // Convert the add button into remove button of the new row
                $("#" + tableID).find('#' + new_row_id).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                    .attr('onclick', 'removeTableRow("' + tableID + '","' + new_row_id + '")');

                // Icon change of the remove button of the new row
                $("#" + tableID).find('#' + new_row_id).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
                // data-number attribute update of the new row
                $('#' + tableID).find('tbody tr').last().attr('data-number', last_row_number);


                // Get all select box elements from the new row, reset the selected value, and change the name of select box
                const all_select_box = $("#" + tableID).find('#' + new_row_id).find('select');
                all_select_box.val(''); // value reset
                all_select_box.prop('selectedIndex', 0);
                for (i = 0; i < all_select_box.length; i++) {
                    const name_of_select_box = all_select_box[i].name;
                    const updated_name_of_select_box = name_of_select_box.replace('[0]', '[' + final_total_row + ']'); //increment all array element name
                    all_select_box[i].name = updated_name_of_select_box;
                }


                // Get all input box elements from the new row, reset the value, and change the name of input box
                const all_input_box = $("#" + tableID).find('#' + new_row_id).find('input');
                all_input_box.val(''); // value reset
                for (i = 0; i < all_input_box.length; i++) {
                    const name_of_input_box = all_input_box[i].name;
                    const updated_name_of_input_box = name_of_input_box.replace('[0]', '[' + final_total_row + ']');
                    all_input_box[i].name = updated_name_of_input_box;
                }


                // Get all textarea box elements from the new row, reset the value, and change the name of textarea box
                const all_textarea_box = $("#" + tableID).find('#' + new_row_id).find('textarea');
                all_textarea_box.val(''); // value reset
                for (i = 0; i < all_textarea_box.length; i++) {
                    const name_of_textarea = all_textarea_box[i].name;
                    const updated_name_of_textarea = name_of_textarea.replace('[0]', '[' + final_total_row + ']');
                    all_textarea_box[i].name = updated_name_of_textarea;
                    $('#' + new_row_id).find('.readonlyClass').prop('readonly', true);
                }


                // var TotalRows = parseInt(rowCount) + 2;
                // var ChakingArray = [10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200];
                //
                // if(jQuery.inArray(TotalRows, ChakingArray) !== -1){
                //     $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-danger').addClass('btn-primary');
                // }else{
                //     $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                //         .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
                //     $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
                // }


                // Table footer adding with add more button
                if (final_total_row > 3) {
                    const check_tfoot_element = $('#' + tableID + ' tfoot').length;
                    if (check_tfoot_element === 0) {
                        const table_header_columns = $('#' + tableID).find('thead th');
                        let table_footer = document.getElementById(tableID).createTFoot();
                        table_footer.setAttribute('id', 'autoFooter')
                        let table_footer_row = table_footer.insertRow(0);
                        for (i = 0; i < table_header_columns.length; i++) {
                            const table_footer_th = table_footer_row.insertCell(i);
                            // if this is the last column, then push add more button
                            if (i === (table_header_columns.length - 1)) {
                                table_footer_th.innerHTML = '<a class="btn btn-sm btn-success addTableRows" title="Add more" onclick="addTableRow(\'' + tableID + '\', \'' + template_row_id + '\')"><i class="fa fa-plus"></i></a>';
                            } else {
                                table_footer_th.innerHTML = '<b>' + table_header_columns[i].innerHTML + '</b>';
                            }
                        }
                    }
                }


                $("#" + tableID).find('#' + new_row_id).find('.onlyNumber').on('keydown', function (e) {
                    //period decimal
                    if ((e.which >= 48 && e.which <= 57)
                        //numpad decimal
                        || (e.which >= 96 && e.which <= 105)
                        // Allow: backspace, delete, tab, escape, enter and .
                        || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                        // Allow: Ctrl+A
                        || (e.keyCode == 65 && e.ctrlKey === true)
                        // Allow: Ctrl+C
                        || (e.keyCode == 67 && e.ctrlKey === true)
                        // Allow: Ctrl+V
                        || (e.keyCode == 86 && e.ctrlKey === true)
                        // Allow: Ctrl+X
                        || (e.keyCode == 88 && e.ctrlKey === true)
                        // Allow: home, end, left, right
                        || (e.keyCode >= 35 && e.keyCode <= 39)) {
                        var $this = $(this);
                        setTimeout(function () {
                            $this.val($this.val().replace(/[^0-9.]/g, ''));
                        }, 4);
                        var thisVal = $(this).val();
                        if (thisVal.indexOf(".") != -1 && e.key == '.') {
                            return false;
                        }
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error');
                        return false;
                    }
                }).on('paste', function (e) {
                    var $this = $(this);
                    setTimeout(function () {
                        $this.val($this.val().replace(/[^.0-9]/g, ''));
                    }, 4);
                });

                // Datepicker initialize of the new row
                $("#" + tableID).find('.datepicker').datetimepicker({
                    viewMode: 'years',
                    format: 'DD-MMM-YYYY',
                    extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                    maxDate: 'now',
                    minDate: '01/01/1905'
                });


                // Datepicker initialize of the new row
                // $("#" + tableID).find('.datepicker').datetimepicker({
                //     viewMode: 'years',
                //     format: 'YYYY',
                //     extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                //     // maxDate: 'now',
                //     minDate: '01/01/1905'
                // });
            } // end of addTableRow() function

            // Remove Table row script
            function removeTableRow(tableID, removeNum) {
                $('#' + tableID).find('#' + removeNum).remove();
                let current_total_row = $('#' + tableID).find('tbody tr').length;
                if (current_total_row <= 3) {
                    const tableFooter = document.getElementById('autoFooter');
                    if (tableFooter) {
                        tableFooter.remove();
                    }
                }
            }


        })

    </script>

@endsection
