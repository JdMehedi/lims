@extends('public_home.front')

@section('header-resources')
    <style>
        @media (max-width: 480px) {
            .notice_img{
                width: 100%;
                height: 100%;
            }
        }
        @media (min-width: 481px) {
            .notice_img{
                width: 100%;
                max-height: 520px;
            }
        }
    </style>
@endsection

@section ('body')
    <div class="container">
        <div class="singlePageDesign">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="notice-title"><i class="fa fa-bell" aria-hidden="true"></i>
                                <strong>{!! trans('messages.notice') !!}
                                    : {{ App::isLocale('bn') ? $noticeData->heading : $noticeData->heading_en }}</strong>
                            </h4>
                        </div>
                        <div class="panel-body">

                        <p>
                            <span class="text-bold">{{ trans('messages.notice_details.publish_date') }} :</span>
                            <span class="{{ App::isLocale('bn') ? 'input_ban' : '' }}">{{ date('h:i | d/m/Y', strtotime($noticeData->updated_at)) }}</span>
                        </p>

                            <img class="notice_img" src="{!! $noticeData->photo ? '/'.$noticeData->photo : '' !!}" alt="" >

                        <p>
                            {!! App::isLocale('bn') ? $noticeData->details : $noticeData->details_en !!}
                        </p>

                        @if(!empty($noticeData->notice_document))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div id="docTabs" style="margin:10px;">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tabs-auth" data-toggle="tab">{{ trans('messages.notice_details.file') }}</a>
                                            </li>
                                        </ul><!-- Tab panes -->

                                        <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="tabs-auth">
                                                    <?php
                                                    $fileUrl = public_path() . '/' . $noticeData->notice_document;
                                                    if (file_exists($fileUrl)) {
                                                    ?>
                                                    <object style="display: block; margin: 0 auto;width:100%;"
                                                            height="1260"
                                                            type="application/pdf"
                                                            data="<?php echo url($noticeData->notice_document); ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH">
                                                    </object>
                                                    <?php } else { ?>
                                                    {{ trans('messages.notice_details.file_not_exist') }}
                                                    <?php } ?> {{-- checking notice file is existed --}}
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
