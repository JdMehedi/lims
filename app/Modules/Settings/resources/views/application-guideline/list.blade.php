<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) die('no access right!');
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
                        <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.application_guideline')!!}</strong></strong></h5>
                    </div>
                    <div class="float-right">
                        @if(ACL::getAccsessRight('settings','A'))
                            <a class="" href="{{ url('/settings/application-guideline/create') }}">
                                {!! Form::button('<i class="fa fa-plus"></i> <b>'. trans('messages.new_application_guideline') .'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="card-body">
                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Inner Service Guideline</th>
                            <th>Public Service Guideline</th>
                            <th>Status</th>
                            <th>{!!trans('messages.available_sevices.action')!!}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
                    url: '{{url("settings/get-guideline")}}',
                    method: 'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'service_name', name: 'service_name'},
                    {data: 'pdf_file', name: 'pdf_file'},
                    {data: 'pdf_file_public', name: 'pdf_file_public'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection
