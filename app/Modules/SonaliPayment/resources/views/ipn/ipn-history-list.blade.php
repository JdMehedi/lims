<?php
$accessMode = \App\Modules\SonaliPayment\Library\SonaliPaymentACL::getAccessRight('SonaliPayment');
if (!\App\Modules\SonaliPayment\Library\SonaliPaymentACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact system admin for more information');
}
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
                        <h5><i class="fa fa-list"></i> <strong>{!! trans('SonaliPayment::messages.ipn_history_list') !!}</strong></h5>
                    </div>
                    <div class="float-right">
                        {{--@if(ACL::getAccessRight('settings','A'))--}}
                        {{--<a class="" href="{{ url('/settings/create-notice') }}">--}}
                        {{--{!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('SonaliPayment::messages.new_notice_btn'). '</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}--}}
                        {{--</a>--}}
                        {{--@endif--}}
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="list" class="table table-striped table-bordered dt-responsive" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Request Ip</th>
                                <th>Trans Id</th>
                                <th>Pay Mode</th>
                                <th>Trans Time</th>
                                <th>Ref. Trans No</th>
                                <th>Trans Status</th>
                                <th>Trans Amount</th>
                                <th>Pay Amount</th>
                                <th>Request Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ipn_history as $value)
                                <tr>
                                    <td>{{ $value->request_ip }}</td>
                                    <td>{{ $value->transaction_id }}</td>
                                    <td>{{ $value->pay_mode_code }}</td>
                                    <td>{{ $value->trans_time }}</td>
                                    <td>{{ $value->ref_tran_no }}</td>
                                    <td>{{ $value->trans_status }}</td>
                                    <td>{{ $value->trans_amount }}</td>
                                    <td>{{ $value->pay_amount }}</td>
                                    <td>
                                        @if ($value->is_authorized_request == 1)
                                            <label class='btn btn-xs btn-success'>Valid</label>
                                        @else
                                            <label class='btn btn-xs btn-danger'>Wrong</label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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

    <script>
        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength": 10
            });
        });

    </script>
@endsection

