@if (!in_array($app_status_id, [-1, 5]))
    {!! Form::open(['url' => url('spg/payment/store'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'payment_form', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}
    <input type="hidden" name="encoded_process_type_id" value="{{ $encoded_process_type_id }}" />
    <input type="hidden" name="encoded_app_id" value="{{ $encoded_app_id }}" />
    <input type="hidden" name="encoded_payment_step_id" value="{{ $encoded_payment_step_id }}" />
@endif

<div class="card card-magenta border border-magenta">
    <div class="card-header">
       {{ $payment_name }}
    </div>
    <div class="card-body">

        <div class="row form-group">
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('contact_name', 'Contact name', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('contact_name', $contact_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('contact_email', 'Contact email', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::email('contact_email', $contact_email, ['class' => 'form-control input-md required email', 'readonly']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('contact_no', 'Contact phone', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('contact_no', $contact_phone, ['class' => 'form-control input-md required phone_or_mobile', 'readonly']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('contact_address', 'Contact address', ['class' => 'col-md-5 text-left required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('contact_address', $contact_address, ['class' => 'form-control input-md required', 'readonly']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('pay_amount', 'Pay amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('pay_amount', $pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('vat_on_pay_amount', 'VAT on pay amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('vat_on_pay_amount', $vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('total_amount', 'Total Amount', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::text('total_amount', $total_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    {!! Form::label('payment_status', 'Payment Status', ['class' => 'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        <span class="label label-warning">Not Paid</span>
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
    @if (!in_array($app_status_id, [-1, 5]))
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success">Submit Payment</button>
        </div>
    @endif
</div>

@if (!in_array($app_status_id, [-1, 5]))
    {!! Form::close() !!}
@endif
