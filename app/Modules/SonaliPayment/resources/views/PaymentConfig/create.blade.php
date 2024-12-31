<?php
$accessMode = \App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment');
if (!\App\Modules\SonaliPayment\Library\SonaliPaymentACL::isAllowed($accessMode, 'A'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('content')

    @include('partials.messages')

{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
            <div class="card card-magenta border border-magenta">
                <div>
                    <h5 class="card-header"><strong>New payment configuration </strong></h5>
                </div>

            {!! Form::open(array('url' => url('/spg/store-payment-configuration'),'method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <!-- /.panel-heading -->
                <div class="card-body">
                    <div class="form-group row">
                        {!! Form::label('process_type_id','Process type: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('process_type_id', $processTypes,'', ['class'=>'form-control bnEng required']) !!}
                            {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{$errors->has('payment_step_id') ? 'has-error' : ''}}">
                        {!! Form::label('payment_step_id','Payment Step: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('payment_step_id', $paymentSteps,'', ['class'=>'form-control bnEng required']) !!}
                            {!! $errors->first('payment_step_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row{{$errors->has('payment_name') ? 'has-error' : ''}}">
                         {!! Form::label('payment_name','Payment Name : ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('payment_name', '', ['class'=>'form-control required','placeholder'=>'Payment Name']) !!}
                            {!! $errors->first('payment_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row{{$errors->has('amount') ? 'has-error' : ''}}">
                        {!! Form::label('amount','Amount(Tk.): ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('amount', '', ['class'=>'form-control required']) !!}
                            <span class="text-warning">N.B: Payment amount can be changed or modified only in an inactive state</span>
                            {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <!-- <div class="form-group row {{$errors->has('vat_tax_percent') ? 'has-error' : ''}}">
                        {!! Form::label('vat_tax_percent','Vat-Tax(%): ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('vat_tax_percent', '', ['class'=>'form-control number']) !!}
                            {!! $errors->first('vat_tax_percent','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_percent') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_percent','Transaction charge(%): ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_percent', '', ['class'=>'form-control number']) !!}
                            {!! $errors->first('trans_charge_percent','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_min_amount') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_min_amount','Transaction charge Min amount: ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_min_amount', '', ['class'=>'form-control number', 'id'=>'']) !!}
                            {!! $errors->first('trans_charge_min_amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_max_amount') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_max_amount','Transaction charge Max amount: ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_max_amount', '', ['class'=>'form-control number', 'id'=>'']) !!}
                            {!! $errors->first('trans_charge_max_amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

 -->
                    <div class="form-group row {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            <label>{!! Form::radio('status', '1', ['class'=>'cursor form-control required']) !!}
                                Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '0', ['class'=>'cursor form-control required']) !!}
                                Inactive</label>
                            &nbsp;&nbsp;
                            {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div><!-- /.box -->


                <div class="card-footer">
                    <div class="float-left">
                        <a href="{{ url('spg/payment-configuration') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="float-right">
                        @if(\App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment','A'))
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            {!! Form::close() !!}<!-- /.form end -->
            </div>
{{--        </div>--}}
{{--    </div>--}}

@endsection
