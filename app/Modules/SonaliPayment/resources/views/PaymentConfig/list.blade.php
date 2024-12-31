<?php
$accessMode = \App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment');
if (!\App\Modules\SonaliPayment\Library\SonaliPaymentACL::isAllowed($accessMode, 'V'))
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
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong>Payment configuration</strong></h5>
                    </div>
                    <div class="float-right">
                        @if(\App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment','A'))
                            <a class="" href="{{ url('/spg/create-payment-configuration') }}">
                                {!! Form::button('<i class="fa fa-plus"></i>  <b>Create payment configuration</b> ', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Process type</th>
                                <th>Payment step</th>
                                <th>Payment Name</th>
                                <th>Amount(Tk.)</th>
                              <!--   <th>Vat-Tax(%)</th>
                                <th>Charge(%)</th>
                                <th>Charge min(Tk.)</th>
                                <th>Charge max(Tk.)</th> -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
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
                    url: '{{url("spg/get-payment-configuration-details-data")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
//                    {data: 'sl_no', name: 'sl_no'},
                    {data: 'drop_down_label', name: 'drop_down_label'},
                    {data: 'payment_step', name: 'payment_step'},
                    {data: 'payment_name', name: 'payment_name'},
                    {data: 'amount', name: 'amount'},
                    // {data: 'vat_tax_percent', name: 'vat_tax_percent'},
                    // {data: 'trans_charge_percent', name: 'trans_charge_percent'},
                    // {data: 'trans_charge_min_amount', name: 'trans_charge_min_amount'},
                    // {data: 'trans_charge_max_amount', name: 'trans_charge_max_amount'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection

