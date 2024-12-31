@extends('public_home.front')
@push('style')
    <style>
        a {
            /*color: #3c8dbc !important;*/
        }
    </style>
@endpush

<?php
$jsonData = json_decode($pdfCertificate->json_object, true);
?>
@section ('body')
    <div class="container">
        <div class="row" style="padding: 30px;">
            {{--@if($pdfCertificate)--}}
            {{--<div class="col-md-12">--}}
            {{--<div class="area">--}}
            {{--<p>Download URL: gfgfgf</p>--}}
            {{--<textarea id="txtarea" style="height:50px;resize:none;width: 100% !important;"--}}
            {{--onClick="SelectAll('txtarea');">{!! $pdfCertificate->certificate_link !!}</textarea>--}}
            {{--</div>--}}
            {{--<div>&nbsp;</div>--}}
            {{--<div class="button downloadlink" style="float:left;">--}}
            {{--<a href="{!! $pdfCertificate->certificate_link !!}" class="btn btn-info myButton" download><b>Download</b></a>--}}
            {{--</div>--}}
            {{--</div>--}}



            @if($pdfCertificate)
            <div class="col-11 col-md-11 col-sm-11 col-md-offset-1">
                <div class="row" style="overflow-x: auto;">
                    <div class="col-12 col-sm-12 col-md-12">
                        <table class="table table-bordered table-bg-white">
                            <tbody>
                                <tr>
                                    <td colspan=3" class="text-center">
                                        <h4><b>{!!trans('Web::messages.company_name')!!}:  </b> {{$pdfCertificate->company_name}}
                                        </h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{!!trans('Web::messages.document_ref')!!}</b></td>
                                    <td colspan="2"><a  style="color: #3c8dbc" href="{{ Request::url() }}">{{ Request::url() }}</a></td>
                                </tr>
                                <tr>
                                    <td><b>{!!trans('Web::messages.document_source')!!}</b></td>
                                    <td>
                                        <div class="pull-left">
                                            <a style="color: #3c8dbc" href="{!! $pdfCertificate->certificate_link !!}"
                                            target="_blank">{!!  \Illuminate\Support\Str::limit($pdfCertificate->certificate_link, $limit = 40, $end = '...') !!}</a>
                                        </div>
                                    </td>
{{--                                    <td>--}}
{{--                                        <div class="pull-right">--}}
{{--                                            <a  download href="{{$pdfCertificate->certificate_link}}" target="_blank"--}}
{{--                                                class="btn btn-sm btn-success no-print"><i class="fa fa-download"></i>--}}
{{--                                                Download--}}
{{--                                                Certificate</a>--}}
{{--                                        </div>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                    </td>--}}
                                </tr>
                                <tr>
                                    <td><b>{!!trans('Web::messages.service_name')!!}</b></td>
                                    <td colspan="2">{{$pdfCertificate->process_name}}</td>
{{--                                    {{$pdfCertificate->group_name}}--}}
                                </tr>
                                @if(isset($IssueAndExpireDate) && $IssueAndExpireDate)
                                <tr>
{{--                                    <td><b>License Duration</b></td>--}}
                                    <td><b>License Validity</b></td>
{{--                                    <td>{{$IssueAndExpireDate->expiry_date}}</td>--}}
                                    <td>{{\Carbon\Carbon::parse($IssueAndExpireDate->expiry_date)->format('d F, Y')}}</td>
                                </tr>
                                @endif
{{--                                <tr>--}}
{{--                                    <td><b>Company Name</b></td>--}}
{{--                                    <td colspan="3">{{$pdfCertificate->company_name}}</td>--}}
{{--                                </tr>--}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <table class="table table-bordered table-bg-white">
                            <tbody>
                            <tr>
                                <td rowspan="4" width="34%"><b>{!!trans('Web::messages.applicant')!!}</b></td>
                                <td colspan="3">{{$pdfCertificate->applicant_name}}</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{$pdfCertificate->applicant_email}}</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{$pdfCertificate->applicant_time}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <table class="table table-bordered table-bg-white">
                            <tbody>
                            <tr>
                                <td width="34%"><b>{!!trans('Web::messages.approver')!!}</b></td>
{{--                                <td>{{$pdfCertificate->approver_name}}</td>--}}
                                <td>BTRC</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <button class="btn btn-default pull-right no-print" onclick="javascript:window.print()">
                            <b><i class="fa fa-print"></i> Print</b>
                        </button>
                    </div>
                </div>
            </div>

            @else
            <div class="col-12 col-sm-12 col-md-12 text-center">

                <h1 class="alert alert-danger text-danger">CERTIFICATE NOT FOUND</h1>
            </div>
            @endif
        </div>
        <br>
    </div>
@endsection
