<?php $accessMode = ACL::getAccsessRight('reportv2');
if (!ACL::isAllowed($accessMode, 'V')) die('no access right!');
?>


@extends('layouts.admin')

@section('header-resources')
    @include('partials.datatable-css')
    <style>
        .small-box {
            margin-bottom: 0;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    @include('partials.messages')
    <link rel="stylesheet" href="{{ asset('css/admin-oss.css') }}" />
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="{{ asset('js/admin-oss.js') }}"></script>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">

                <div class="panel-heading">
                    <div class="pull-left"></div>
                    <div class="pull-right">
                        @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '10x101' || Auth::user()->user_type == '15x151' || Auth::user()->user_type == '7x707')
                            @if(ACL::getAccsessRight('reportv2','A'))
                                <a class="" href="{{ url('/reportv2/create') }}">
                                    {!! Form::button('<i class="fa fa-plus"></i> <b>Add New Report</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                </a>
                            @endif
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            @if(Auth::user()->user_type !='11x101')
                            <li class="nav-item active">
                                <a data-toggle="tab" href="#list_1" aria-expanded="true">
                                    <b>Recent</b>
                                </a>
                            </li>
                            @endif
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a data-toggle="tab" href="#list_2" aria-expanded="true">--}}
                            {{--                                    <b>My Favourite</b>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            <li class="nav-item all_reports">
                                <a data-toggle="tab" href="#list_3" aria-expanded="false" id="all_report">
                                    <b>All Reports</b>
                                </a>
                            </li>
                            @if(Auth::user()->user_type !='11x101')
                            <li class="nav-item unpublished_reports">
                                <a data-toggle="tab" href="#list_4" aria-expanded="false">
                                    <b>Unpublished</b>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="list_1" class="tab-pane active">

                        @if(Auth::user()->user_type !='11x101')

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label style="font-size: 18px;">Last 4 report</label>
                                </div>
                                <div class="panel-body">

                                    @foreach($getLast4Reports as $item)
                                        <a href="{!! url('reportv2/view/'. Encryption::encode($item->report_id."/Published" )) !!}">
                                            <div class="form-group col-lg-3 col-md-3 col-xs-6">
                                                <div class="small-box"
                                                     +
                                                     style="color: #fff; border-radius: 10px; padding: 15px; background-image: linear-gradient(to right, #7C5CF5, #9B8BF7);">
                                                    <div class="row text-center">
                                                        <i class="fa fa-files-o fa-3x"></i>
                                                    </div>
                                                    <br>
                                                    <div class="row text-center">
                                                        <label for="">{{ substr($item->report_title, 0,  60) }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                            </div>


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label style="font-size: 18px;">Favourites</label>
                                </div>
                                <div class="panel-body">

                                    @foreach($getFavouriteList['fav_report'] as $favourite)
                                        <a href="{!! url('reportv2/view/'. Encryption::encode($favourite->report_id."/Favourites" )) !!}">
                                            <div class="form-group col-lg-3 col-md-3 col-xs-6">
                                                <div class="small-box"
                                                     style="color: #fff; border-radius: 10px; padding: 15px; background-image: linear-gradient(to right, #7C5CF5, #9B8BF7);">
                                                    <div class="row text-center">
                                                        <i class="fa fa-files-o fa-3x"></i>
                                                    </div>
                                                    <br>
                                                    <div class="row text-center">
                                                        <label for="">{{ substr($favourite->report_title, 0,  60) }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                            </div>

                            @foreach($Categories as $row)
                                <?php
                                $singleData = explode('@', $row->groupData);
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label style="font-size: 18px;">{{$row->category_name}}</label>
                                    </div>
                                    <div class="panel-body">

                                        @foreach($singleData as $singleRow)
                                            <?php
                                            $value = explode('=', $singleRow);
                                            ?>

                                            <a href="{!! url('reportv2/view/'. Encryption::encode($value[0]."/Published" )) !!}">
                                                <div class="form-group col-lg-3 col-md-3 col-xs-6">
                                                    <div class="small-box"
                                                         style="color: #fff; border-radius: 10px; padding: 15px; background-image: linear-gradient(to right, #69D4D4, #6CD2D5);">
                                                        <div class="row text-center">
                                                            <i class="fa fa-files-o fa-3x"></i>
                                                        </div>
                                                        <br>
                                                        <div class="row text-center">
                                                            <label for="">{{ substr($value[1], 0,  60) ??""}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach


                                    </div>
                                </div>
                            @endforeach

                        
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label style="font-size: 18px;">Uncategorized</label>
                                </div>
                                <div class="panel-body">
                                    @foreach($uncategorized as $item)
                                        <a href="{!! url('reportv2/view/'. Encryption::encode($item->report_id."/Published" )) !!}">
                                            <div class="form-group col-lg-3 col-md-3 col-xs-6">
                                                <div class="small-box"
                                                     style="color: #fff; border-radius: 10px; padding: 15px; background-image: linear-gradient(to right, #EC6060, #FC8170);">
                                                    <div class="row text-center">
                                                        <i class="fa fa-files-o fa-3x"></i>
                                                    </div>
                                                    <br>
                                                    <div class="row text-center">
                                                        <label for="">{{ substr($item->report_title, 0,  60) }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                            </div>
                            @endif
                            {{--                            <div class="panel panel-default">--}}
                            {{--                                <div class="panel-heading">--}}
                            {{--                                    <label style="font-size: 18px;">Published</label>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="panel-body">--}}

                            {{--                                    @foreach($publishedReports as $published)--}}
                            {{--                                        <a href="{!! url('reportv2/view/'. Encryption::encode($published->report_id."/Published" )) !!}">--}}
                            {{--                                            <div class="form-group col-lg-3 col-md-3 col-xs-6">--}}
                            {{--                                                <div class="small-box"--}}
                            {{--                                                     style="color: #fff; border-radius: 10px; padding: 15px; background-image: linear-gradient(to right, #5373DF, #458DDD);">--}}
                            {{--                                                    <div class="row text-center">--}}
                            {{--                                                        <i class="fa fa-files-o fa-3x"></i>--}}
                            {{--                                                    </div>--}}
                            {{--                                                    <br>--}}
                            {{--                                                    <div class="row text-center">--}}
                            {{--                                                        <label for="">{{$published->report_title}}</label>--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </a>--}}
                            {{--                                    @endforeach--}}

                            {{--                                </div>--}}
                            {{--                            </div>--}}

                        </div>
                        {{--                        <div id="list_2" class="tab-pane">--}}
                        {{--                            <div class="panel-body">--}}
                        {{--                                <div class="table-responsive">--}}
                        {{--                                    <table id="fav_list" class="table table-striped table-bordered dt-responsive nowrap"--}}
                        {{--                                           cellspacing="0" width="100%">--}}
                        {{--                                        <thead>--}}
                        {{--                                        <tr>--}}
                        {{--                                            <th>Title</th>--}}
                        {{--                                            <th>Category</th>--}}
                        {{--                                            <th>Last Modified</th>--}}
                        {{--                                            <th>Action</th>--}}
                        {{--                                        </tr>--}}
                        {{--                                        </thead>--}}
                        {{--                                        <tbody>--}}
                        {{--                                        @foreach($getFavouriteList['fav_report'] as $row)--}}
                        {{--                                            <tr>--}}
                        {{--                                                <td>{!! $row->report_title !!}</td>--}}
                        {{--                                                <td>{!! $row->category_name !!}</td>--}}
                        {{--                                                <td>{!! date('d-m-Y', strtotime($row->updated_at)) !!}</td>--}}
                        {{--                                                <td>--}}
                        {{--                                                    @if(\App\Libraries\UtilFunction::isAllowedToViewFvrtReport($row->report_id))--}}
                        {{--                                                        @if(ACL::getAccsessRight('reportv2','V'))--}}
                        {{--                                                            <a href="{!! url('reportv2/view/'. Encryption::encode($row->report_id."/Favourites" )) !!}"--}}
                        {{--                                                               class="btn btn-xs btn-primary">--}}
                        {{--                                                                <i class="fa fa-folder-open-o"></i> Open--}}
                        {{--                                                            </a>--}}
                        {{--                                                        @endif--}}
                        {{--                                                        @if(ACL::getAccsessRight('report','E'))--}}
                        {{--                                                            {!! link_to('reportv2/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}--}}
                        {{--                                                        @endif--}}
                        {{--                                                    @endif--}}
                        {{--                                                </td>--}}
                        {{--                                            </tr>--}}
                        {{--                                        @endforeach--}}
                        {{--                                        </tbody>--}}
                        {{--                                    </table>--}}
                        {{--                                </div>--}}
                        {{--                                <!-- /.table-responsive -->--}}
                        {{--                            </div>--}}
                        {{--                            <!-- /.panel-body -->--}}
                        {{--                        </div>--}}

                        


                        <div id="list_3" class="tab-pane all_reports">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Last Modified</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($getList['result'] as $row)
                                            <tr>
                                                <td>{!! $row->report_title !!}</td>
                                                <td>{!! $row->category_name !!}</td>
                                                <td>{!! date('d-m-Y', strtotime($row->updated_at)) !!}</td>
                                                <td>
                                                    @if(ACL::getAccsessRight('reportv2','V'))
                                                        <?php
                                                        $status = $row->status == 1 ? "Published" : "unpublished"
                                                        ?>
                                                        <a href="{!! url('reportv2/view/'. Encryption::encode($row->report_id."/".$status )) !!}"
                                                           class="btn btn-xs btn-primary">
                                                            <i class="fa fa-folder-open-o"></i> Open
                                                        </a>
                                                    @endif
                                                    @if(ACL::getAccsessRight('reportv2','E'))
                                                        {!! link_to('reportv2/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}

                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <div id="list_4" class="tab-pane unpublished_reports">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table cellspacing="0" width="100%"
                                           class="table table-striped table-bordered nowrap">
                                        <thead>
                                        <tr>
                                            <td>Title</td>
                                            <th>Category</th>
                                            <th>Last Modified</th>
                                            <td>Action</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($getUnpublishedList as $row)
                                            <tr>
                                                <td>{!! $row->report_title !!}</td>
                                                <td>{!! $row->category_name !!}</td>
                                                <td>{!! date('d-m-Y', strtotime($row->updated_at)) !!}</td>
                                                <td>
                                                    @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '10x101' || Auth::user()->user_type == '15x151' || Auth::user()->user_type == '7x707')
                                                        <?php
                                                        $status = $row->status == 1 ? "Published" : "unpublished"
                                                        ?>
                                                        <a href="{!! url('reportv2/view/'.  Encryption::encode($row->report_id."/".$status )) !!}"
                                                           class="btn btn-xs btn-primary">
                                                            <i class="fa fa-folder-open-o"></i> Open
                                                        </a>
                                                        {!! link_to('reportv2/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.col-lg-12 -->

@endsection

@section('footer-script')

    @include('partials.datatable-js')
    @include('partials.reportV2-js')

    <script>

        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength": 25
            });
        });

        $(function () {
            $('#fav_list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength": 25
            });
        });

    </script>
@endsection
