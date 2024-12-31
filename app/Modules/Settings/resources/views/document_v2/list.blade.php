<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
    <link rel="stylesheet" src="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
@endsection
@section('content')
    @include('partials.messages')

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <div class="float-left">
                        <h5 ><strong><i class="fa fa-list"></i> Document management</strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs nav-item">
                            <li class="active">
                                <a class="nav-link active" data-toggle="tab" role="tab" href="#serviceDocTab" aria-controls="serviceDocTab"
                                   aria-expanded="true">
                                    <strong><i class="fa fa-file-text fa-fw"></i> Process Document</strong>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" role="tab" href="#docTab" aria-controls="docTab"
                                   aria-expanded="true">
                                    <strong><i class="fa fa-file"></i> Document Name</strong>
                                </a>
                            </li>
                        </ul>

                        <br/>
                        <div class="tab-content">
                            <div id="serviceDocTab" class="tab-pane fade in active show">
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header section_heading1">
                                        <strong style="line-height: 35px;"><i class="fa fa-file-text fa-fw"></i> Process
                                            document list</strong>
                                        @if(ACL::getAccsessRight('settings','E'))
                                            <button type="button" class="float-right btn btn-success"
                                                    onclick="openModal(this)"
                                                    data-action="{{ url('/settings/document-v2/service/create') }}">
                                                <i class="fa fa-plus"></i> <b>New Process Document </b>
                                            </button>
                                        @endif
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="serviceDocList"
                                                   class="table table-striped table-bordered dt-responsive"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Document name</th>
                                                    <th>Process type</th>
{{--                                                    <th>Document type</th>--}}
                                                    <th>Required status</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->

                            <div id="docTab" class="tab-pane fade">
                                <div class="card">
                                    <div class="card-header section_heading1">
                                        <strong style="line-height: 35px;"><i class="fa fa-file"></i> Document name
                                            list</strong>
                                        @if(ACL::getAccsessRight('settings','E'))
                                            <button type="button" class="float-right btn btn-success"
                                                    onclick="openModal(this)"
                                                    data-action="{{ url('settings/document-v2/create') }}">
                                                <i class="fa fa-plus"></i> <b>New Document </b>
                                            </button>
                                        @endif
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="documentList"
                                                   class="table table-striped table-bordered dt-responsive"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Document name</th>
                                                    <th width="20%" >Simple File</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(function () {

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                const new_tab = e.target.getAttribute('aria-controls') // newly activated tab
                // const previous_tab = e.relatedTarget.getAttribute('aria-controls') // previous active tab
                if (new_tab === 'docTab') {
                    loadDocuments();
                }
            });

            /**
             * Document List loading
             * @type {jQuery}
             */
            $('#serviceDocList').DataTable({
                iDisplayLength: 10,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{url("settings/document-v2/service-document-list")}}',
                    method: 'get'
                },
                columns: [
                    {data: 'sl', name: 'sl', searchable: false},
                    {data: 'doc_name', name: 'doc_name', searchable: true},
                    {data: 'process_type', name: 'process_type', searchable: true},
                    // {data: 'doc_type', name: 'doc_type', searchable: true},
                    {data: 'is_required', name: 'is_required', searchable: false},
                    {data: 'status', name: 'status', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        let is_loaded_doc_list = 0;

        function loadDocuments() {
            if (is_loaded_doc_list === 0) {
                is_loaded_doc_list = 1;
                $('#documentList').DataTable({
                    iDisplayLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ajax: {
                        url: '{{url("settings/document-v2/document-list")}}',
                        method: 'get'
                    },
                    columns: [
                        {data: 'sl', name: 'sl', searchable: false},
                        {data: 'name', name: 'name', searchable: true},
                        {data: 'simple_file', name: 'name', searchable: false},
                        {data: 'status', name: 'status', searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    "aaSorting": []
                });
            }
        }


        function openModal(btn) {
            //e.preventDefault();
            const this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#myModal .load_modal').html(data);
                    } else {
                        $('#myModal .load_modal').html('Unknown Error!');
                    }
                    $('#myModal').modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection
