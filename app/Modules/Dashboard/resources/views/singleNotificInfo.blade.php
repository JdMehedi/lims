<?php

use App\Libraries\ACL;

$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('no access right!');
}
?>

@extends('layouts.admin')
@section('body')

    <style>
        .panel-head {
            background: #4B8DF8;
            padding: 2px 10px;
            border-radius: 0;
            box-sizing: border-box;
            border: 1px solid #4B8DF8;
            border-bottom: 0;
        }
    </style>


    @if(Auth::user()->user_type == '1x101')
        <!-- <select class="js-example-basic-single" id="allUserNotific" name="state">
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>
                  <option value="AL">Alabama</option>

                  <option value="WY">Wyoming</option>
                </select> -->

    @endif

    <?php



    if(Request::segment(1) == 'single-notification'){  ?>
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header ">
                <h4 class="card-title" id=""><i class="fa fa-bell" aria-hidden="true"></i> <strong style="color: white">Notification</strong></h4>
            </div>
            <div class="card-body" style="background: #ffffff;border: 1px solid #4B8DF8; border-top: 0;">

                <div class="card card-default">
                    <div class="card-header" style="padding: 5px">
                        <div class="row">
                            <div class="col-md-12 card-title">
                                <div class="col-md-6"><span class="float-left"> <h4><b>Title:</b>  {{ $singleNotificInfo->email_subject }}</h4></span>
                                </div>
                                <div class="col-md-6"><span
                                            class="float-right"> <h4>Date : {{ $singleNotificInfo->created_at }}</h4></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        @php
                            if (!empty($singleNotificInfo->attachment_certificate_name)) {
                                $app_id = $singleNotificInfo->app_id;
                                $attachment_content_split = explode('.', $singleNotificInfo->attachment_certificate_name);
                                if (!empty($attachment_content_split[0]) && !empty($attachment_content_split[1])) {
                                    $certificate_link_query = DB::select(DB::raw("select $attachment_content_split[1] from $attachment_content_split[0] where id =$app_id and $attachment_content_split[1] != ''"));
                                    if (empty($certificate_link_query)) {
                                        echo 'Attachment data not found for this email.';
                                    }
                                    $attachment = $attachment_content_split[1];
                                    $singleNotificInfo->email_content = str_replace('{$attachment}', $certificate_link_query[0]->$attachment, $singleNotificInfo->email_content);

                                }
                            }
                        @endphp
                        <p>{!! $singleNotificInfo->email_content !!}</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <?php
    }else{ ?>

    <div class="col-sm-12">
        <div class="card card-success">
            <div class="card-header">
                <h4 class="card-title" id=""><i class="fa fa-bell" aria-hidden="true"></i> <strong>All notification</strong></h4>
            </div>
            <div class="card-body">
                @if (count($notificationsAll) > 0)
                    <?php $i = 0; ?>
                    <div class="panel-group" id="accordion">
                        @foreach($notificationsAll as $notific)
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4>
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse{{$notific->id}}" style="color: #000">
                                            <div class="float-left">Title:</b> {{ $notific->email_subject }}</div>
                                            <div class="float-right">Date
                                                : {{ date('d-M-Y h:i:s A', strtotime($notific->created_at)) }}</div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$notific->id}}"
                                     class="panel-collapse collapse @if($i == 0) in @endif">
                                    <div class="card-body">
                                        @php
                                            if (!empty($notific->attachment_certificate_name)) {
                                                $app_id = $notific->app_id;
                                                $attachment_content_split = explode('.', $notific->attachment_certificate_name);
                                                if (!empty($attachment_content_split[0]) && !empty($attachment_content_split[1])) {
                                                    $certificate_link_query = DB::select(DB::raw("select $attachment_content_split[1] from $attachment_content_split[0] where id =$app_id and $attachment_content_split[1] != ''"));

                                                    if (empty($certificate_link_query)) {
                                                        echo 'Attachment data not found for this email.';
                                                        continue;
                                                    }
                                                    $attachment = $attachment_content_split[1];
                                                    $notific->email_content = str_replace('{$attachment}', $certificate_link_query[0]->$attachment, $notific->email_content);

                                                }
                                            }
                                        @endphp
                                        {!! $notific->email_content !!}
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                        @endforeach
                    </div>
                @else
                    <h4 class="text-center">You have no notification</h4>
                @endif
            </div>
        </div>
    </div>
    <?php
    }

    ?>
    <!-- <script>
     $(document).ready(function() {
        $('#allUserNotific').select2();
    });
</script> -->
@endsection

@section('footer-script')
@endsection

