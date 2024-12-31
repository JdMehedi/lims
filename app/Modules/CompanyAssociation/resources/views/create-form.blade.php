<?php
$accessMode = ACL::getAccsessRight('CompanyAssociation');
if (!ACL::isAllowed($accessMode, '-V-'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('header-resources')
    <style>
        .select2-container--default .select2-selection--multiple {
            border-radius: 0px !important;

        }
        .select2-selection__rendered {
            color: #555 !important;
            padding: 0px !important;
        }
        .select2-container .select2-search--inline .select2-search__field {
            padding-left: 5px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ mix("assets/plugins/select2/css/select2.min.css") }}">
@endsection
@section('content')

    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5><strong>Company Association Request</strong></h5>
                </div>
                <div class="panel-body">
                    <div class="col-lg-10">
                        {!! Form::open(array('url' => 'company-association/store', 'method' => 'post','id'=>'formId')) !!}


                        <div class="form-group col-md-12">
                            {!! Form::label('', 'Applicant Name:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {{ \App\Libraries\CommonFunction::getUserFullName() }}
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            {!! Form::label('', 'Current Company:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <?php $i = 1;?>
                                @foreach($current_company_lists as $company)
                                    <dd>{{$i++}}. {!! $company->company_name !!}</dd>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            {!! Form::label('select_company', 'Requested Company to associate:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <select name="requested_company_ids[]" class="form-control limitedNumbSelect2"
                                        data-placeholder="Select company to request to associate" style="width: 100%;"
                                        multiple="multiple">
                                    @foreach($companyList as $company)
                                        @if(!in_array($company->id, $current_company_ids))
                                            <option value="{{ \App\Libraries\Encryption::encodeId($company->id) }}">{{ $company->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger" style="font-size: 10px; font-weight: bold">[All of your associated company are selected in the list. you can add a new company.]</span>
                                {!! $errors->first('requested_company_ids','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            {!! Form::label('select_company', 'Requested Company to remove:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <select name="remove_req_company_ids[]" class="form-control limitedNumbSelect2"
                                        data-placeholder="Select company to remove from association"
                                        style="width: 100%;"
                                        multiple="multiple">
                                    @foreach($companyList as $company)
                                        @if(in_array( $company->id, $current_company_ids))
                                            <option value="{{ \App\Libraries\Encryption::encodeId($company->id) }}">{{ $company->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger" style="font-size: 10px; font-weight: bold">[All of your associated company in the list. you can select a company from list to remove.]</span>
                                {!! $errors->first('remove_req_company_ids','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            {!! Form::label('user_remarks', 'Remarks:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9 maxTextCountDown">
                                {!! Form::textarea('user_remarks','',['class'=>'form-control','placeholder'=>'Remarks', 'size' => '3x2', 'maxlength' => '250']) !!}
                                {!! $errors->first('user_remarks','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="col-md-10">
                        <div class="pull-left">
                            <a href="{{ url('company-association/list') }}" class="btn btn-default"><i
                                        class="fa fa-close"></i> Close</a>
                        </div>
                        <div class="pull-right">
                            @if(ACL::getAccsessRight('CompanyAssociation','-A-'))
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('footer-script')

    <script src="{{ mix("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });

            $("#formId").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        {{--//textarea count down--}}
        $('.maxTextCountDown').maxlength();
    </script>
@endsection
