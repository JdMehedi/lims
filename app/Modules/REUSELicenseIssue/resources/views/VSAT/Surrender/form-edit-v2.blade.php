<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input-disable {
        pointer-events: none;
    }

    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .p-r-0 {
        padding-right: 0;
    }

    .m-l-auto {
        margin-left: auto;
    }

    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
    }

    /*.card-magenta:not(.card-outline) > .card-header {*/
    /*    display: inherit;*/
    /*}*/

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .card-header::after {
        display: none;
    }

    .d\:flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    mr-2 {
        margin-right: 0.5rem;
    }

    .wizard {
        overflow: visible;
    }

    .wizard > .content {
        overflow: visible;
    }

    .wizard > .actions {
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

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard > .actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard > .content > .body label {
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

    /*.wizard>.steps>ul>li {*/
    /*    width: 33.2% !important;*/
    /*}*/

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

    .card-header {
        border-bottom: 0px;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <div class="card-header d:flex justify-between">
                <h4 class="card-header">Application for VSAT-HUB Operator/ VSAT User License/ VSAT RT User License Surrender</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye mr-2"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
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
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>
                {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                {{--Basic Information--}}
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'renew-form-edit'])
                    {{-- VSAT License Information --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            VSAT License Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('license_categories', 'License Categories', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('license_category') ? 'has-error' : '' }}">
                                            {!! Form::select('license_category', [''=>'Select', '1'=>'VSAT HUB Operator','2'=>'VSAT User','3'=>'VSAT RT User'],$appInfo->license_category,['class' => 'form-control', 'id'=> 'license_categories'])!!}
                                            {!! $errors->first('license_category', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('origin_or_satelite', 'Origin or Satelite Type', ['class' => 'col-md-4 p-r-0']) !!}
                                        <div
                                            class="col-md-8 {{ $errors->has('origin_or_satelite') ? 'has-error' : '' }}">
                                            {!! Form::select('origin_or_satelite', [''=>'Select', '1'=>'National Satelite','2'=>'Foreign Satelite'],$appInfo->sattelite_type, ['class' => 'form-control', 'id'=> 'origin_or_satelite']) !!}
                                            {!! $errors->first('origin_or_satelite', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Company info --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    @includeIf('common.subviews.ContactPerson', ['mode' => 'edit'])

                    @includeIf('common.subviews.Shareholder', ['mode' => 'edit'])
                </fieldset>

                {{--VSAT Information--}}
                <h3>VSAT Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{--SatelliteCommunication Service Provider Information--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            SatelliteCommunication Service Provider Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Service Provider Name</th>
                                    <th width="">Service Detials</th>
                                    <th width="">Location</th>
                                    <th style="width:10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vsat_service_provider as $index=>$item)
                                    <tr row_id="satelite_{{$index+1}}">
                                        <td class="d-flex justify-content-center">{{$loop->iteration}}</td>
                                        <td><input type="text" class="form-control service_provider"
                                                   id="service_provider_{{ $index }}"
                                                   name="service_provider[{{ $index }}]"
                                                   value="{{$item->service_provider_ame}}"
                                                   placeholder="Enter Service Provider Name"></td>
                                        <td><input type="text" class="form-control service_details"
                                                   id="service_details_{{ $index }}"
                                                   name="service_details[{{ $index }}]"
                                                   value="{{$item->service_detials}}"
                                                   placeholder="Enter Service Detials"></td>
                                        <td><input type="text" class="form-control service_location"
                                                   id="service_location_{{ $index }}"
                                                   name="service_location[{{ $index }}]" value="{{$item->location}}"
                                                   placeholder="Enter Location"></td>
                                        @if($index ===0)
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                            </td>
                                        @else
                                            <td class="d-flex justify-content-center">
                                                <button
                                                    class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                    type="button"><i class="fa fa-minus"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--VSAT Hub Operator Information--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            VSAT Hub Operator Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="vsat_tbl">
                                <thead>
                                <tr>
                                    <th class="text-center" width="10%">SL .No</th>
                                    <th width="40%">Place Name (Installed/Existing)</th>
                                    <th>Geographical Location (Measured by Set)</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vsat_hub_info as $index=>$item)
                                    <tr row_id="vsat_{{$index+1}}">
                                        <td class="d-flex justify-content-center">{{$loop->iteration}}</td>
                                        <td><input type="text" class="form-control vsat_place_name"
                                                   id="vsat_place_name_{{ $index }}"
                                                   name="vsat_place_name[{{ $index }}]" value="{{$item->place_name}}"
                                                   placeholder="Place name(installed existing)"></td>
                                        <td><input type="number" class="form-control vsat_location"
                                                   id="vsat_location_{{ $index }}" name="vsat_location[{{ $index }}]"
                                                   value="{{$item->geographical_location}}"
                                                   placeholder="Geographical Location (Measured by Set)"></td>
                                        @if($index === 0)
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                            </td>
                                        @else
                                            <td class="d-flex justify-content-center">
                                                <button
                                                    class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                    type="button"><i class="fa fa-minus"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--Technical Specification --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Technical Specification/ Catalogue
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="tariffChart_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Name</th>
                                    <th width="">Type</th>
                                    <th width="">Manufacturer</th>
                                    <th width="">Country of Origin</th>
                                    <th width="">Power Output</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vsat_technical_specification as $index=>$item)
                                    <tr row_id="technical_{{$index+1}}">
                                        <td class="d-flex justify-content-center">{{$loop->iteration}}</td>
                                        <td><input type="text" class="form-control technical_name"
                                                   id="technical_name_{{ $index }}" name="technical_name[{{ $index }}]"
                                                   value="{{$item->name}}" placeholder="Enter  name"></td>
                                        <td><input type="text" class="form-control technical_type"
                                                   id="technical_type_{{ $index }}" name="technical_type[{{ $index }}]"
                                                   value="{{$item->type}}" placeholder="Enter type"></td>
                                        <td><input type="text" class="form-control technical_manufacturer"
                                                   id="technical_manufacturer_{{ $index }}"
                                                   name="technical_manufacturer[{{ $index }}]"
                                                   value="{{$item->manufacturer}}" placeholder="Enter manufacturer">
                                        </td>
                                        <td><input type="text" class="form-control technical_country_of_Origin"
                                                   id="technical_country_of_Origin_{{ $index }}"
                                                   name="technical_country_of_Origin[{{ $index }}]"
                                                   value="{{$item->country_of_origin}}"
                                                   placeholder="Enter country of Origin"></td>
                                        <td><input type="text" class="form-control technical_power_output"
                                                   id="technical_power_output_{{ $index }}"
                                                   name="technical_power_output[{{ $index }}]"
                                                   value="{{$item->power_output}}" placeholder="Enter power output">
                                        </td>
                                        @if($index === 0)
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                            </td>
                                        @else
                                            <td class="d-flex justify-content-center">
                                                <button
                                                    class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                    type="button"><i class="fa fa-minus"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--List of Equipment for Monitoring--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            List of Equipment for Monitoring
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="listOfEquipment_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Equipment Name</th>
                                    <th width="">Storage Capacity</th>
                                    <th width="">Data</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($vsat_equipment_list as $index=>$item)
                                    <tr row_id="listOfEquipment_{{$index+1}}">
                                        <td class="d-flex justify-content-center">{{$loop->iteration}}</td>
                                        <td><input type="text" class="form-control list_equipment"
                                                   id="list_equipment_{{ $index }}" value="{{$item->equipment_name}}"
                                                   placeholder="Equipment Name" name="list_equipment[{{ $index }}]">
                                        </td>
                                        <td><input type="text" class="form-control list_storage"
                                                   id="list_storage_{{ $index }}" value="{{$item->storage_capacity}}"
                                                   placeholder="Storage Capacity" name="list_storage[{{ $index }}]">
                                        </td>
                                        <td><input type="text" class="form-control list_data"
                                                   id="list_data_{{ $index }}" value="{{$item->data}}"
                                                   placeholder="Data" name="list_data[{{ $index }}]"></td>
                                        @if($index === 0)
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                            </td>
                                        @else
                                            <td class="d-flex justify-content-center">
                                                <button
                                                    class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                    type="button"><i class="fa fa-minus"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>

                {{--Attachment Declration--}}
                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'edit'])
                    {{--Required Documents--}}
                    {{--                    <div class="card card-magenta border border-magenta">--}}
                    {{--                        <div class="card-header" id="reqDoc">--}}
                    {{--                            Required Documents for attachment--}}
                    {{--                        </div>--}}
                    {{--                        <div class="card-body" style="padding: 15px 25px;">--}}
                    {{--                            <input type="hidden" id="doc_type_key" name="doc_type_key">--}}
                    {{--                            <div id="docListDiv"></div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--Declaration--}}
                    <div class="card card-magntea border border-magenta mt-4">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any Application for License of VSAT-HUB Operator been rejected
                                                before?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? 'selected' : '' , ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No',$appInfo->declaration_q1 == 'No' ? 'selected' : '', ['class'=>'form-check-input ', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px; display:none;" id="if_declaration_q1_yes">
                                                {{ Form::textarea('declaration_q1_text',$appInfo->declaration_q1_text, array('class' =>'form-control input', 'id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any Application for License of VSAT-HUB Operator been rejected
                                                before?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? 'selected' : '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none;" id="if_declaration_q2_yes">
                                                {{ Form::textarea('declaration_q2_text', $appInfo->declaration_q2_text, array('class' =>'form-control input', 'id'=>'declaration_q2_text','placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>

                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant(s) any Share Holder(s) Partners) hold any other
                                                Operator from the Commision?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes',$appInfo->declaration_q3 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No',$appInfo->declaration_q3 == 'No' ? 'selected' : '', ['class'=>'form-check-input ', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none;" id="if_declaration_q3_yes">
                                                @php $declarationRequireClass = !empty($appInfo->declaration_q3_doc) ? 'declarationFile': 'required' @endphp
                                                {{ Form::file('declaration_q3_images',['class'=> "form-control input $declarationRequireClass",'id'=>'declaration_q3_images','style'=>'margin-bottom: 20px;'])}}
                                                @isset($appInfo->declaration_q3_doc)
                                                    @php $fileNameArray = explode("/",$appInfo->declaration_q3_doc) @endphp
                                                    <a href="{{ asset($appInfo->declaration_q3_doc)}}"
                                                       data-image-preview="{{ end($fileNameArray) }}"
                                                       id="declaration_q3_images_preview" target="_blank"
                                                       class="btn btn-sm btn-danger" style="margin-top: 5px">Open</a>
                                                @endisset
                                            </div>

                                        </li>
                                        <li><span class="i_we_dynamic">I/We</span> hereby certify that <span
                                                class="i_we_dynamic">I/We</span> have carefully read the
                                            guidelines/terms and conditions, for the license and <span
                                                class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                            conditions therein. (Terms and Conditions of License Guidelines for VSAT-HUB
                                            Operator are available at www.btrc.gov.bd)
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                            certify that <span class="i_we_dynamic">I/We</span> have carefully read the
                                            section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span
                                                class="i_we_dynamic">I/We</span> are not disqualified from obtaining the
                                            license.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that this application if found incomplete in any respect and or if found
                                            with conditional compliance shall be summarily rejected.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that if at any time any information furnished for obtaining the license is
                                            found incorrect then the license if granted on the basis of such application
                                            shall deemed to be cancelled and shall be liable for action as per
                                            Bangladesh Telecommunication Regulation Act, 2001.
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Payment and Submit--}}
                <h3> {{ ($appInfo->status_id == 5 )? 'Re-Submit' : ' Submit' }}</h3>
                <fieldset>
                    {{-- Service Fee Payment --}}
                    @if($appInfo->status_id != 5)
                        <div id="payment_panel"></div>
                    @elseif(($appInfo->status_id == 5) && (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0))
                        <div id="payment_panel"></div>
                    @endif

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms','required']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/vsat-license-issue/list/'. Encryption::encodeId(1)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                        </button>
                    @endif

                    @if($appInfo->status_id == 5)
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md" onclick="ReSubmitForm()"
                                value="Re-submit" name="actionBtn">Re-submit
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

<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">

        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close"
                                                                                 data-dismiss="modal"
                                                                                 style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the
                                        expiration of duration of its license or else the license shall be cancelled as
                                        per law and penal action shall follow, if the licensee continues its business
                                        thereafter without valid license. The late fees/fines shall be recoverable under
                                        the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to
                                        submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will
                                        not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh
                                        Telecommunication
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
    let company_type = "{{$companyInfo->org_type}}";
    if (company_type == "") {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('I/We');
    } else if (company_type == 1) {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('I');
    } else {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('We');
    }

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
    getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},
        'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});
    @endforeach
    @endisset

    @if($appInfo->declaration_q1 == 'Yes')
    $('#if_declaration_q1_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q2 == 'Yes')
    $('#if_declaration_q2_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q3 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif

    let selectCountry = '';
    $(document).ready(function () {
        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                let errorStatus = false;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        // alert("The value of the '% of share field' should be a total of 100.");
                        new swal({
                            type: 'error',
                            text: 'The value of the % of share field should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }
                }

                $(".shareholder_nationality").each(function (){
                    let id = $(this).attr('id');
                    let countryCode = $(this).val();
                    console.log(countryCode);
                    let lastId = id.split('_')[2];
                    if(countryCode == 18){
                        let nidValue = $("#shareholder_nid_"+lastId).val();
                        if(nidValue.length == 10 || nidValue.length == 13 || nidValue.length == 17){
                            errorStatus = false;
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please provide valid NID number.',
                            });
                            $(this).addClass('error')
                            errorStatus = true;
                        }
                    }

                });

                if (newIndex == 3) {
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {

                            } else {
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        } else {
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
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
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if (!form.valid()) errorStatus = true;
                if (errorStatus) return false;
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
                    form.find('#submitForm').css('display', 'none');
                    // form.steps("next");
                }
                if (currentIndex === 3) {
                    form.find('#submitForm').css('display', 'inline');
                    // form.steps("next");
                } else {
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

        let popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")) {
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
            if (isCheckedAcceptTerms()) return false;
            openPreviewV2();
        });


        function attachmentLoad() {
            let reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            let company_type_id = parseInt($("#company_type_id").val()); //order 2
            let industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            let investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            let key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');

        let today = new Date();
        let yyyy = today.getFullYear();

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


        $(function () {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

        //form & field operation
        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
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

        // nationality
        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];
            console.log(lastRowId);
            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#passportBlock_' + lastRowId).hide();
                $('#shareholder_nid_' + lastRowId).addClass('required');
                $('#shareholder_passport_' + lastRowId).removeClass('required');
            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#passportBlock_' + lastRowId).show();
                $('#shareholder_passport_' + lastRowId).addClass('required');
                $('#shareholder_nid_' + lastRowId).removeClass('required');
            }
        });

        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$appInfo->org_type}}";
        if (company_type == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (company_type == 1) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        $('.add_row').click(function () {
            let btn = $(this);
            btn.prop("disabled", true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                async: false,
                type: "POST",
                url: "{{ url('vsat-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled", false);
                }
            });
            getHelpText();

        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });


    });

    function mobile_no_validation(id) {
        var onlyNumber = $("#" + id).val();
        var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode ?? "880";
        var regex = /^01[0-9]{9}$/;
        var result = regex.test(onlyNumber);

        if (countryCode === '880') {
            if (onlyNumber === "") {
                console.log('label:', 0, `#${id}`)
                $(`#${id}`).addClass('error');
            } else if (result === false) {
                console.log('label:', 1)
                $(`#${id}`).addClass('error');
            } else {
                console.log('label:', 2)
                $(`#${id}`).removeClass('error');
            }
        }
    }

    function generateBase64String(source, destination) {
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

    // display payment panel
    const fixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
    const payOrderInfo = @json($pay_order_info);

    @if($appInfo->status_id != 5)
    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        fixed_amounts, JSON.stringify(payOrderInfo));
    @endif
    @if(($appInfo->status_id == 5) &&
        (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0)
    )
    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        fixed_amounts, JSON.stringify(payOrderInfo));
    @endif

    function openPreview() {
        if (isCheckedAcceptTerms()) return false;
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function openPreviewV2() {
        if (isCheckedAcceptTerms()) return false;

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
        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function isCheckedAcceptTerms() {
        var checkbox = document.querySelector('#accept_terms');
        var errorStatus = false;
        if (!checkbox.checked) {
            checkbox.classList.add('error');
            errorStatus = true;
        } else {
            checkbox.classList.remove('error');
            errorStatus = false;
        }
        return errorStatus;
    }

    function deleteContactRow(element) {
        element.remove();
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

    function ReSubmitForm(){
        if (isCheckedAcceptTerms()) return false;
        document.querySelector("#application_form").submit();
    }

</script>

