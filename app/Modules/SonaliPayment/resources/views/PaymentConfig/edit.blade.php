<?php
$accessMode = \App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment');
if (!\App\Modules\SonaliPayment\Library\SonaliPaymentACL::isAllowed($accessMode, 'E'))
    die('no access right!');
?>

@extends('layouts.admin')

@section('header-resources')
    @include('partials.datatable-css')
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            {{--Stakeholder modal--}}
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content load_modal"></div>
                </div>
            </div>

            {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
            {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <h5 ><strong>Edit Payment Configuration</strong></h5>
                </div>

            {!! Form::open(array('url' => url('/spg/update-payment-configuration/'.$encrypted_id),'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <!-- /.panel-heading -->
                <div class="card-body">
                    <div class="form-group row {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                        {!! Form::label('process_type_id','Process Type: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('process_type_id', $processTypes, $data->process_type_id, ['class'=>'form-control bnEng required']) !!}
                            {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                     <div class="form-group row {{$errors->has('payment_step_id') ? 'has-error' : ''}}">
                        {!! Form::label('payment_step_id','Payment Step: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('payment_step_id', $paymentSteps,$data->payment_step_id, ['class'=>'form-control bnEng required']) !!}
                            {!! $errors->first('payment_step_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>


                    <div class="form-group row {{$errors->has('payment_name') ? 'has-error' : ''}}">
                         {!! Form::label('payment_name','Payment Name : ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('payment_name',$data->payment_name, ['class'=>'form-control required','placeholder'=>'Payment Name']) !!}
                            {!! $errors->first('payment_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('amount') ? 'has-error' : ''}}">
                        {!! Form::label('amount','Amount(Tk.): ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('amount', $data->amount, ['class'=>'form-control required']) !!}
                            <span class="text-warning">N.B: Payment amount can be changed or modified only in an inactive state</span>
                            {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <!-- <div class="form-group row {{$errors->has('vat_tax_percent') ? 'has-error' : ''}}">
                        {!! Form::label('vat_tax_percent','Vat-Tax(%): ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('vat_tax_percent', $data->vat_tax_percent, ['class'=>'form-control number']) !!}
                            {!! $errors->first('vat_tax_percent','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_percent') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_percent','Trns Amount(%): ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_percent', $data->trans_charge_percent, ['class'=>'form-control number']) !!}
                            {!! $errors->first('trans_charge_percent','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_min_amount') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_min_amount','Trns Charge Min Amount: ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_min_amount', $data->trans_charge_min_amount, ['class'=>'form-control number']) !!}
                            {!! $errors->first('trans_charge_min_amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{$errors->has('trans_charge_max_amount') ? 'has-error' : ''}}">
                        {!! Form::label('trans_charge_max_amount','Trns Charge Max Amount: ',['class'=>'col-md-3 ']) !!}
                        <div class="col-md-9">
                            {!! Form::number('trans_charge_max_amount', $data->trans_charge_max_amount, ['class'=>'form-control number']) !!}
                            {!! $errors->first('trans_charge_max_amount','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

 -->
                    <div class="form-group row {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status: ',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            @if(\App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment','E'))
                                &nbsp;&nbsp;
                                <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required']) !!}
                                    Active</label>
                                &nbsp;&nbsp;
                                <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required']) !!}
                                    Inactive</label>
                            @endif
                            {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div><!-- /.box -->

                <div class="card-footer">
                    <div class="float-left">
                        <a   href="{{ url('spg/payment-configuration') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>

                        {!! showAuditLog($data->updated_at, $data->updated_by) !!}
                    </div>
                    <div class="float-right">
                        @if(\App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment','E'))
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            {!! Form::close() !!}<!-- /.form end -->
            </div>

            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left" >
                        <strong><i class="fa fa-list"></i> Payment Distribution</strong>
                    </div>
                    <div class="float-right">
                        @if(\App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment','A'))
                            <a class="addDistribution" data-toggle="modal" data-target="#myModal"
                               onclick="openModal(this)"
                               data-action="{{ url('/spg/stakeholder-distribution/'.Encryption::encodeId($data->id)) }}">
                                {!! Form::button('<i class="fa fa-plus"></i> <strong>New stakeholder distribution </strong>', array('type' => 'button', 'class' => 'float-right btn btn-default')) !!}
                            </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table  table-bordered" id="list">
                                <thead>
                                    <tr>
                                        <th >SL</th>
                                        <th >Account Name</th>
                                        <th >Account Number</th>
                                        <th >Purpose(SBL)</th>
                                        <th >Amount</th>
                                        <th >Fixed/Unfixed</th>
                                        <th >Distribution type</th>
                                        <th >Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    @include('partials.datatable-js')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("spg/get-payment-distribution-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.pay_config_id = "{{ Encryption::encodeId($data->id) }}";
                    }
                },
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data: 'stakeholder_ac_name', name: 'stakeholder_ac_name'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'purpose_sbl', name: 'purpose_sbl'},
                    {data: 'amount', name: 'amount'},
                    {data: 'fix_status', name: 'fix_status'},
                    {data: 'distribution_type_name', name: 'distribution_type_name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        function openModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        console.log(data);
                        $('#myModal .load_modal').html(data);
                    } else {
                        $('#myModal .load_modal').html('Unknown Error!');
                    }
                    $('#myModal').modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection <!--- footer script--->
