<div class="panel panel-info">
    <div class="panel-heading">
        <h5><strong>{{ $payment_info->payment_name }}</strong></h5>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('contact_name', 'Contact name', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('contact_name', $payment_info->contact_name, ['class' => 'form-control input-md required']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('contact_email', 'Contact email', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::email('contact_email', $payment_info->contact_email, ['class' => 'form-control input-md required email']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('contact_no', 'Contact phone', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('contact_no', $payment_info->contact_no, ['class' => 'form-control input-md required phone_or_mobile']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('address', 'Contact address', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('address', $payment_info->address, ['class' => 'form-control input-md required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('pay_amount', 'Pay amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('pay_amount', $payment_info->pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('vat_on_pay_amount', 'VAT on pay amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('vat_on_pay_amount', $payment_info->vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('total_amount', $payment_info->total_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('payment_status', 'Payment Status', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        @if ($payment_info->sfp_payment_status == 0)
                            <span class="label label-warning">Pending</span>
                        @elseif($payment_info->sfp_payment_status == -1)
                            <span class="label label-info">In-Progress</span>
                        @elseif($payment_info->sfp_payment_status == 1)
                            <span class="label label-success">Paid</span>
                        @elseif($payment_info->sfp_payment_status == 2)
                            <span class="label label-danger">-Exception</span>
                        @elseif($payment_info->sfp_payment_status == 3)
                            <span class="label label-warning">Waiting for Payment Confirmation</span>
                        @else
                            <span class="label label-warning">invalid status</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning no-margin mb-0" role="alert">
                    <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the
                    Sonali Bank system and those will be visible here after payment submission.
                </div>
            </div>
        </div>
    </div>
</div>
