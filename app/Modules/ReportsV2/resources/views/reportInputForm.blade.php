<?php $accessMode = ACL::getAccsessRight('reportv2');
if (!ACL::isAllowed($accessMode, 'V')) die('no access right!');
?>

@extends('layouts.admin')

@section('header-resources')
    @include('partials.datatable-css')

    <link rel="stylesheet" href="{{ asset("assets/plugins/datepicker-oss/css/bootstrap-datetimepicker.min.css") }}" />
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h5><strong><?php echo $report_data->report_title.''; ?></strong></h5>
                    </div>
                    <div class="pull-right">
                        @if(ACL::getAccsessRight('reportv2','E'))
                            {!! link_to('reportv2/edit/'. $report_id,'Edit',['class' => 'btn btn-default']) !!}

                        @endif
                        @if($fav_report_info)
                            @if($fav_report_info->status == 1)
                                <a href="{{ url('reportv2/remove-from-favourite/'.$report_id) }}" class="btn btn-info">
                                    <b>Remove From Favourite</b>
                                    &nbsp;<i class="fa fa-remove"></i>
                                </a>
                            @elseif($fav_report_info->status == 0)
                                <a href="{{ url('reportv2/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                                    <b>Add to Favourite</b>
                                    &nbsp;<i class="fa fa-check-square-o"></i>
                                </a>
                            @endif
                        @else
                            <a href="{{ url('reportv2/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                                <b>Add to Favourite</b>
                                &nbsp;<i class="fa fa-check-square-o"></i>
                            </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('ReportsV2::input-form')
                </div><!-- /.box-body -->
            </div>
        </div>
    </div>

    @if($report_data->is_crystal_report != 0 && $report_data->is_crystal_report != null)

        <div id="report_list_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="crystal_list" class="table table-striped table-bordered dt-responsive nowrap"
                               cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Date Time</th>
                                <th>Parameter</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="report_id" value="{{$report_id }}">
        <input type="hidden" name="pdfurl" value="{{env('PDF_API_BASE_URL')}}">

    @endif

@endsection

@section('footer-script')

    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/datepicker-oss/js/bootstrap-datetimepicker.js") }}"></script>
    @include('partials.datatable-js')

    <script type="text/javascript">
        var report_id = $('input[name="report_id"]').val();
        var table = null;
        var crystal_report_btn = $('form#report_form button#crystal_report_generate');

        $(document).ready(function () {
            var report_id = "{{$report_id}}";
            table = $('#crystal_list').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax: {
                    url: '{{url("reportv2/show-crystal-report-data")}}',
                    type: "POST",
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.report_id = report_id;
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'search_keys', name: 'search_keys'},
                    {data: 'pdf_download_link', name: 'pdf_download_link', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        $(document).on('click', 'form#report_form button#crystal_report_generate', function (e) {
            e.preventDefault();

            var form = $('form#report_form');
            var btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;' + btn_content);

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize() + '&crystal_report=crystal_report',
                dataType: 'json',
                success: function (response) {
                    if (response.responseCode == 1) {
                        btn.prop('disabled', true);
                        checkgenerator(report_id, 'crystal_report_paper_request', response.report_request_id);
                    } else {
                        alert(response.error);
                        btn.html(btn_content);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                },
                beforeSend: function (xhr) {

                }
            });
            return false; // keeps the page from not refreshing
        });

        function checkgenerator(id, request_id, report_request_id) {
            pdfurl = $('input[name="pdfurl"]').val();
            $.ajax({
                url: '{{ url('/reportv2/ajax-crystal-report-feedback') }}',
                type: "POST",
                data:
                    {
                        report_id: id,
                        report_request_id: report_request_id,
                        pdfurl: pdfurl
                    },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.responseCode === 1) {
                        if (response.data === 1) {
                            // Need to show download & regenerate link
                            showDownloadPanel(id, request_id, response.ref_id);
                        } else if (response.data === -1) {
                            alert('Information not eligible!');
                            table.ajax.reload();
                            return false;
                        } else if (response.data === 2) {
                            myVar = setTimeout(checkgenerator, 5000, id, request_id, report_request_id);
                        }

                    } else {

                        alert('Whoops there was some problem please contact with system admin.');

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                },
                beforeSend: function (xhr) {

                }
            });
            return false; // keeps the page from not refreshing
        }

        function showDownloadPanel(id, request_id, ref_id) {
            pdfurl = $('input[name="pdfurl"]').val();
            reportsql = $('input[name="reportsql"]').val();
            $.ajax({
                url: '{{ url('/reportv2/update-download-panel') }}',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data:
                    {
                        ref_id: ref_id,
                        reportsql: reportsql
                    },
                success: function (response) {
                    if (response.responseCode == 1) {
                        if (request_id == "crystal_report_paper_request") {
                            $('form#report_form span#crystal_report_btn').html(response.data);
                            table.ajax.reload();
                        } else {
                            window.location.reload();
                        }
                    } else {
                        window.location.reload();
                    }
                }
            });
            return false; // keeps the page from not refreshing
        }


        $(function () {
            $(".datepicker").datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD',
                // maxDate: 'now', //commented out on April 04,23 (req- Anamul vaia)
            });
        });
        $(function () {
            $(".datetimepicker").datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });
        $(function () {
            $(".timepicker").datetimepicker({
                format: 'HH:mm',
            });
        });
    </script>
@endsection
