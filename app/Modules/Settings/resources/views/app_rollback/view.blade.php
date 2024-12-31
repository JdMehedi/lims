@extends('layouts.admin')

@section('page_heading',trans('messages.rollback'))

@section('content')

<!--    --><?php
//    $accessMode = ACL::getAccsessRight('settings');
//    if (!ACL::isAllowed($accessMode, 'ARB')) {
//        die('You have no access right! For more information please contact system admin.');
//    }
//    ?>

    @include('partials.messages')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                <div class="float-right">
                    <div class="btn-group" role="group" aria-label="" >
                        <a type="button" class="btn btn-default" target="_blank" href="{{ url($openAppRoute) }}">
                            <span style="color:#000;">Open Application</span>
                        </a>
{{--                        <a type="button" class="btn btn-success" target="_blank" href="{{ url($BiRoute) }}">--}}
{{--                            View Corresponding Basic Information--}}
{{--                        </a>--}}
                    </div>
                </div>
                <h5>
                    Application Rollback
                </h5>
            </div>

            <div class="card-body">
                <section>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><strong>Tracking no. : </strong>{{ $rollbackAppInfo->tracking_no  }}</li>
                        <li class="breadcrumb-item active" aria-current="page"><strong>App. Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                        <li class="breadcrumb-item active" aria-current="page"><strong>Current Status : </strong>
                            @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                            @else {!! $appInfo->status_name !!}
                            @endif
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <strong>Current Desk :</strong>
                            @if($appInfo->desk_id != 0)
                                {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                            @else
                                Applicant
                            @endif
                        </li>
                        @if($appInfo->user_id)
                            <li class="breadcrumb-item active" aria-current="page">
                                <strong>Current User :</strong>
                                {!! $appInfo->desk_user_name !!}
                            </li>
                        @endif
                    </ol>
                </section>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                <h5><strong> Company Information </strong></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="row">
                                                <label class="col-md-4"><strong>Company:</strong></label>
                                                <div class="col-md-7">
                                                    {{$appInfo->org_nm}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="row">
                                                <label class="col-md-4"><strong>Submission Date:</strong></label>
                                                <div class="col-md-7">
                                                    {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label class="col-md-4"><strong>Department:</strong></label>--}}
{{--                                            <div class="col-md-8">--}}
{{--                                                {{$appInfo->department_name}}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label class="col-md-4"><strong>Sub-Department:</strong></label>--}}
{{--                                            <div class="col-md-8">--}}
{{--                                                {{$appInfo->sub_department_name}}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                <h5><b> View Application Rollback Information </b></h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Caption</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                    </tr>
                                    </thead>
                                    @foreach($data as $key => $data)
                                        <tr>
                                            <td width="33%">{{ $data->caption }}</td>
                                            <td width="33%">{{ $data->old_value }}</td>
                                            <td width="33%">{{ $data->new_value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <div class="row pt-4">
                                    <label class="col-md-2"><strong>Rollback Remarks :</strong></label>
                                    <div class="col-md-10">
                                        {{$rollbackAppInfo->remarks}}
                                    </div>
                                </div>

                            </div>
                        </div>
{{--                    </div>--}}
{{--                </div>--}}

                <div class="clearfix"></div>
            </div>
        </div>
    </div><!-- /.box -->
</div>
@endsection
