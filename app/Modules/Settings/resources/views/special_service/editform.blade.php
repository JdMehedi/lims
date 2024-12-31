<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) die('no access right!');
?>

@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.8.1/skins/content/default/content.min.css" />
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header" style="margin-top: -1px;">
                    <h5 class="card-title pt-2 pb-2"><strong> Create New Service </strong></h5>
                </div>

            {!! Form::open(array('url' => url('/settings/get-service/update'),'method' => 'post', 'class' => '', 'id' => 'app_guideline',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <input type="hidden" name="app_id" value="{{\App\Libraries\Encryption::encodeId($process_type->id)}}">
            <!-- /.panel-heading -->
                <div class="card-body">
                <div class="row " >

            <div class="col-md-6">

            <div class="form-group col-md-12  {{$errors->has('service_name') ? 'has-error' : ''}}" id="service_name">
                {!! Form::label('service_name','Service Full Name:',['class'=>'col-md-6 control-label required-star'],'required') !!}
                <div class="col-md-12">
                    <input class="form-control" name="service_name" type="text" id="service_name" value="{{$process_type->name}}" required readonly>
                    {!! $errors->first('service_name','<span class="text-danger">:message</span>') !!}
                </div>
            </div>

            </div>

            <div class="col-md-6">

            <div class="form-group col-md-12  {{$errors->has('service_short_name') ? 'has-error' : ''}}">
                {!! Form::label('service_short_name','Service Short Name:',['class'=>'col-md-6 control-label required-star']) !!}
                <div class="col-md-12">
                    {!! Form::text('service_short_name', $process_type->drop_down_label, ['class' => 'form-control required ','readonly' => 'true']) !!}
                    {!! $errors->first('service_short_name','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            </div>

           
            </div>
            <!--///////////////////////// Inputs from here//////////////////////// -->
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts,null, ['class' => 'form-control', 'id' => 'reg_office_district','disabled', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], null, ['class' => 'form-control','disabled', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', null, ['class' => 'form-control','readonly' =>'true', 'placeholder' => 'Enter Address', 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>


            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts,null, ['class' => 'form-control', 'id' => 'reg_office_district','disabled', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], null, ['class' => 'form-control','disabled', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', null, ['class' => 'form-control','readonly' =>'true', 'placeholder' => 'Enter Address', 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('reg_office_address2') ? 'has-error' : '' }}">
                                            {!! Form::text('reg_office_address2', null, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'reg_office_address2']) !!}
                                            {!! $errors->first('reg_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>


            <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Applicant Profile
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', 'user name',
                                ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_first_name) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8  {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="iti-flag bd"></span>
                                    <span>+88</span>
                                </span>
                            </div>
                            {!! Form::text('applicant_mobile', '019********', ['class' => 'form-control applicant-mobile',
                                 'readonly' => 'readonly',
                                 'placeholder' => 'Enter Mobile Number']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email','testmail@gmail.com', ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_email) ?? 'readonly',
                                 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone', '93*****', ['class' => 'form-control','readonly', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control', 'disabled','id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control','disabled', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', '', ['class' => 'form-control', 'placeholder' => 'Enter Address','readonly', 'id' => 'applicant_address']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms & Conditions <span style="float: right;"></span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul style="list-style: disc !important;">
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
                                    </ul>
                                </div>
                            </div>
                        </div>
    </div>


    <div class="card card-magenta border border-magenta">
                <div class="card-header">

                    <div class="float-left">
                        <h5><strong>Other Required Data</strong></h5>
                    </div>
                    <div class="float-right">
                        
                            <a class="section_plus_icon" >
                                {!! Form::button('<i class="fa fa-plus"></i> <b> Add More Section' .'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                       
                    </div>

                    
                </div>


                <div id="multi_section">
                <input type="hidden" name="section_no" id="section_no" value="{{count($dynamic_form_data)}}" >
                <?php $index=0; ?>
                @foreach($dynamic_form_data as $key1=>$val1)
                    <div class="card card-magenta border border-magenta" style="margin: 15px;" id="section_{{$key1+1}}">
                    @foreach($val1 as $key2=>$val2)
                        <input type="hidden" name="section_id[]" id="section_id[]" value="{{$key1+1}}" >
                        <div class="card-header">

                                <div class="float-left">
                                    <h5><strong> Section {{$key1+1}}</strong></h5>
                                </div>
                                @if($index>0)
                                <div class="float-right">
                                <i class="fa fa-minus-circle section_minus_icon" id="minus-icon" style="font-size:26px;color:red" aria-hidden="true"></i>
                                </div>
                                @endif
                            
                            </div>
                           
                        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

                            <div class="form-group col-md-12  {{$errors->has('section_name') ? 'has-error' : ''}}" id="section_name">
                                {!! Form::label('section_name','Section Name:',['class'=>'col-md-6 control-label required-star'],'required') !!}
                                <div class="col-md-12">
                                    <input class="form-control" name="section_name[]" type="text" id="section_name[]" value="{{$val2['section_name']}}" required >
                                    {!! $errors->first('section_name','<span class="text-danger">:message</span>') !!}
                                </div>
                            </div>

                             
                            
                            <div class="col-md-12" id="label_table_div" style="padding: 10px;overflow:visible">
                                <table class="table table-bordered" id="label_table" cellspacing="0" width="100%"
                                    style="border: 1px solid lightgrey">
                                    <thead>
                                    <tr>
                                        <th style="background-color: lightgreen" width="50%">Label Name</th>
                                        <th style="background-color: lightgreen; text-align:center" width="30%">Input Type</th>
                                        <th style="background-color: lightgreen; text-align:center" width="20%">Input Labels</th>
                                        
                                        <th style="background-color: lightgreen" width="10%">Action</th>

                                    </tr>
                                    </thead>
                                    
                                    <tbody id="cost_rows" value="{{$key2+1}}">
                                    <?php $input_index=0; ?>
                                    @foreach($val2['section_value'] as $key3=>$val3)
                                    <tr style="background-color: lightgrey">
                                    
                                        <td>
                                            {!! Form::text('label_'.($key2+1).'[]', $value = $val3['input_label'],  array('class'=>'form-control',
                                            'id'=>'label_'.($key2+1).'[]')) !!}
                                        </td>
                                    
                                        <td>
                                            {!! Form::select('input_type_'.($key2+1).'[]',$input_type ,$value = $val3['input_type'],  array('class'=>'form-control input_type',
                                            'id'=>'input_type_'.($key2+1).'[]')) !!}
                                        </td>

                                        @if($val3['input_type']=='Text')
                                        <td>
                                            {!! Form::text('input_names_'.($key2+1).'[]' ,$value = null,  array('class'=>'form-control input_names','placeholder' => '','readonly',
                                            'id'=>'input_names_'.($key2+1).'[]')) !!}
                                        </td>
                                        @else
                                        <td>
                                            {!! Form::text('input_names_'.($key2+1).'[]' ,$value = $val3['input_name'],  array('class'=>'form-control input_names','placeholder' => 'comma separated values',
                                            'id'=>'input_names_'.($key2+1).'[]')) !!}
                                        </td>
                                        @endif
                                        
                                        @if($input_index>0)
                                        <td><i class="fa fa-minus-circle minus_icon"
                                       id="minus-icon"
                                       style="font-size:26px;color:red" aria-hidden="true"></i>
                                        </td>
                                        @else
                                        <td><i class="fa fa-plus-circle plus_icon"
                                            id="plus_icon"
                                            style="font-size:26px;color:green" aria-hidden="true"></i>
                                        </td>
                                        @endif
                                        
                                    </tr>
                                    <?php $input_index++; ?>
                                    @endforeach   
                                    </tbody>
                                    
                                </table>
                            </div>
                            

                        </div>    <!--  end card body -->
                           
                    </div>
                    @endforeach

                    <?php $index++; ?>

                @endforeach
                </div>
               
  <!-- add more sections -->



                <div class="card card-magenta border border-magenta" style="margin: 15px;">
                <div class="card-header">
                   Attachments
                </div>

                <div class="col-md-12" id="attachment_table_div" style="padding: 10px;overflow:visible">
                        <table class="table table-bordered" id="attachment_table" cellspacing="0" width="100%"
                               style="border: 1px solid lightgrey">
                            <thead>
                            <tr>
                                <th style="background-color: lightgreen" width="70%">Label Name</th>
                                <th style="background-color: lightgreen; text-align:center" width="40%">Input Type</th>
                                <th style="background-color: lightgreen" width="10%">Action</th>

                            </tr>
                            </thead>
                            <tbody id="file_rows">
                                <?php $fileindex=0; ?>
                            @foreach($dynamic_form_attachments as $doc_key=>$files)   
                            <tr style="background-color: lightgrey">
                               
                                <td>
                                    {!! Form::text('file_label[]', $value = $files,  array('class'=>'form-control',
                                    'id'=>"file_label[]")) !!}
                                </td>
                               
                                <td>
                                <input type="file" class="form-control" id="file_input_type[]" name="file_input_type[]" disabled >

                                </td>
                                @if($fileindex>0)
                                <td><i class="fa fa-minus-circle attach_minus_icon"
                                       id="minus-icon"
                                       style="font-size:26px;color:red" aria-hidden="true"></i>
                                </td>
                                @else
                                <td><i class="fa fa-plus-circle attach_plus_icon"
                                       id="attach_plus_icon"
                                       style="font-size:26px;color:green" aria-hidden="true"></i>
                                </td>
                                @endif
                               
                            </tr>
                            <?php $fileindex++; ?>
                            @endforeach           
                            </tbody>
                            <tbody>
                           
                            </tbody>
                        </table>
                    </div>
                </div>

                
    </div>

                    

             <!--///////////////////////// Inputs Ends here//////////////////////// -->

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->

                <div class="card-footer">
                    <div class="float-left">
                        <a href="{{ url('/settings/application-guideline') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="float-right">
                        @if(ACL::getAccsessRight('settings','A'))
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            {!! Form::close() !!}<!-- /.form end -->
            </div>
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{ asset('assets\scripts\jquery.validate.js') }}"></script>
    {{--<script src="{{ asset('assets\plugins\tinymce\tinymce.min.js') }}"></script>--}}
    <script src="{{asset('assets\plugins\tinymce\tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            var section_no= parseFloat( $('#section_no').val())+1;
            var tbody_val= 0;

            $("#app_guideline").validate({
                errorPlacement: function () {
                    return true;
                },
            });

            $('#service_guideline').change(function () {
                var mime_type = this.files[0].type;

                if (mime_type != 'application/pdf') {
                    alert("Guideline must be pdf format");
                    return false;
                } else {
                    readFile(this.files[0], function (e) {
                        // use result in callback...
                        pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
                        document.getElementById('page_count').value = pagecount;
                        ready = true;

                    });

                }
            });

            $('#service_guideline_public').change(function () {
                var mime_type = this.files[0].type;

                if (mime_type != 'application/pdf') {
                    alert("Public guideline must be pdf format");
                    return false;
                } else {
                    readFile(this.files[0], function (e) {
                        // use result in callback...
                        pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
                        document.getElementById('page_count_public').value = pagecount;
                        ready = true;

                    });

                }
            });
            
            $(document).on('click', '.plus_icon', function () {
                tbody_val = $(this).parent().parent().parent().attr("value");
                
                $(this).parent().parent().parent().append('<tr>\n' +
                '<td>\n' +
                '<input type="text" id="label_'+tbody_val+'[]" name="label_'+tbody_val+'[]" class="form-control">\n'+
                '</td>\n' +
                '<td>\n' +
                '<select id="input_type_'+tbody_val+'[]" name="input_type_'+tbody_val+'[]" class="form-control input_type">\n'+
                '@foreach($input_type as $key=>$val)' +
                '<option value="{{$key}}">{{$val}}</option>\n'+
                '@endforeach'+
                '</select>\n' +
                '</td>\n' +
                '<td>\n' +
                '<input type="text" placeholder="comma separated values" id="input_names_'+tbody_val+'[]" name="input_names_'+tbody_val+'[]" class="form-control input_names">\n'+
                '</td>\n' +
                '<td><i class="fa fa-minus-circle minus_icon"\n' +
                'id="minus-icon"\n' +
                'style="font-size:26px;color:red" aria-hidden="true"></i></td>\n'  +
                '</tr>\n'
            );
            
        });

        $(document).on('click', '.minus_icon', function () {
            $(this).parent().parent().remove();
            

        });


        $(document).on('click', '.attach_plus_icon', function () {
            $('#file_rows').append('<tr>\n' +
                '<td>\n' +
                '{!! Form::text('file_label[]', $value = null,  array('class'=>'form-control ','id'=>"file_label[]")) !!}\n'+
                '</td>\n' +
                '<td>\n' +
                '<input type="file" class="form-control" id="file_input_type[]" name="file_input_type[]" disabled >\n'+
                '</td>\n' +
                '<td><i class="fa fa-minus-circle attach_minus_icon"\n' +
                'id="minus-icon"\n' +
                'style="font-size:26px;color:red" aria-hidden="true"></i></td>\n'  +
                '</tr>\n'
            );
            
        });

        $(document).on('click', '.attach_minus_icon', function () {
            $(this).parent().parent().remove();
            

        });




        $(document).on('click', '.section_plus_icon', function () {
            $('#multi_section').append('<div class="card card-magenta border border-magenta" style="margin: 15px;" id="section_' +section_no+'">\n' +
            '<input type="hidden" name="section_id[]" id="section_id[]" value="\n'+section_no+
            '" >\n'+
                '<div class="card-header">\n' +
                            ' <div class="float-left">\n' +
                                '<h5><strong>Section\n' + section_no +
                                 '</strong></h5>\n' + 
                            '</div>\n' +
                            '<div class="float-right">\n' +
                            '<i class="fa fa-minus-circle section_minus_icon" id="minus-icon" style="font-size:26px;color:red" aria-hidden="true"></i>\n' +
                            '</div>\n' +
                '</div>\n'+
                ' <div class="card-body">\n' +
                        '<div class="form-group col-md-12  {{$errors->has('section_name') ? 'has-error' : ''}}" id="section_name">\n' +
                            '{!! Form::label('section_name','Section  Name:',['class'=>'col-md-6 control-label required-star'],'required') !!}\n' +
                            '<div class="col-md-12">\n' +
                                '<input class="form-control" name="section_name[]" type="text" id="section_name[]"  required >\n' +
                                '{!! $errors->first('section_name','<span class="text-danger">:message</span>') !!}\n' +
                            '</div>\n' +
                        '</div>\n' +
                '</div>\n' +
                '<div class="col-md-12" id="label_table_div" style="padding: 10px;overflow:visible">\n' +
                            '<table class="table table-bordered" id="label_table" cellspacing="0" width="100%"\n' +
                                'style="border: 1px solid lightgrey">\n' +
                                '<thead>\n' +
                                '<tr>\n' +
                                    '<th style="background-color: lightgreen" width="50%">Label Name</th>\n' +
                                    '<th style="background-color: lightgreen; text-align:center" width="30%">Input Type</th>\n' +
                                    '<th style="background-color: lightgreen; text-align:center" width="20%">Input Labels</th>\n' +
                                    
                                    '<th style="background-color: lightgreen" width="10%">Action</th>\n' +

                                '</tr>\n' +
                                '</thead>\n' +
                                '<tbody id="cost_rows" value="' +section_no +'">\n' +
                                
                                '<tr style="background-color: lightgrey">\n' +
                                
                                    '<td>\n' +
                                    '<input type="text" id="label_'+section_no+'[]" name="label_'+section_no+'[]" class="form-control">\n'+
                                    '</td>\n' +
                                
                                    '<td>\n' +
                                    '<select id="input_type_'+section_no+'[]" name="input_type_'+section_no+'[]" class="form-control input_type">\n'+
                '@foreach($input_type as $key=>$val)' +
                '<option value="{{$key}}">{{$val}}</option>\n'+
                '@endforeach'+
                '</select>\n' +
                                    '</td>\n' +
                                    '<td>\n' +
                                    '<input type="text" placeholder="comma separated values" id="input_names_'+section_no+'[]" name="input_names_'+section_no+'[]" class="form-control input_names">\n'+
                                    '</td>\n' +
                                    '<td><i class="fa fa-plus-circle plus_icon" id="plus_icon" style="font-size:26px;color:green" aria-hidden="true"></i>\n' +
                                    '</td>\n' +
                                '</tr>\n' +
                                        
                                '</tbody>\n' +
                                
                            '</table>\n' +
                        '</div>\n' +
                                    '</div>\n'
            );
            section_no++;
        });


        $(document).on('click', '.section_minus_icon', function () {
            $(this).parent().parent().parent().remove();
            

        });

        $(document).on('change', '.input_type', function () {
            
            if($(this).val()=='Text'){ //text
                $(this).closest('td').siblings().find('.input_names').val('');
                $(this).closest('td').siblings().find('.input_names').prop("readonly", true);
            }else{
                $(this).closest('td').siblings().find('.input_names').prop("readonly", false);
            }
           
            

        });


        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function () {
            $("#area-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });

        });
    </script>
@endsection <!--- footer script--->
