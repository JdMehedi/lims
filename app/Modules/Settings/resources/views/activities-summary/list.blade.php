@extends('layouts.admin')

@section('body')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-success border border-success">
                <div class="card-header">
                    <div class="float-left">
                        <h5><strong><i class="fa fa-list"></i> {!! trans('messages.activities_summary') !!}</strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        @include('partials.messages')
                        <div class="col-lg-12 text-center"><h3>Your Activities' summary for the last 30 days</h3></div>
                        <table id="list" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th style="text-align: center">Task description</th>
                                    <th style="text-align: center">Number</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="text-align: center">1</td>
                                <td>Number of login/logout</td>
                                <td style="text-align: center">{{$user_logs}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">2</td>
                                <td>Total number of action taken on application:
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr style="background: white;">
                                                <th>Type of Application</th>
                                                <th>Number of application</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $totalCount=0;
                                        ?>
                                        @foreach($totalNumberOfAction as $totalData)
                                            <?php
                                            $totalCount+=$totalData->totalApplication;
                                            ?>
                                            <tr>
                                                <td>{{$totalData->name}}</td>
                                                <td>{{$totalData->totalApplication}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>

                                <td style="text-align: center;  vertical-align:middle">{{$totalCount}}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center">3</td>
                                <td>Current pending application in your desk:
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr style="background: white;">
                                            <th>Type of Application</th>
                                            <th>Number of application</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $totalPending = 0;?>
                                        @foreach($currentPendingYourDesk as $currentPendingData)
                                            <?php
                                            $totalPending+=$currentPendingData->totalApplication;
                                            ?>
                                            <tr>
                                                <td>{{$currentPendingData->name}}</td>
                                                <td>{{$currentPendingData->totalApplication}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td  style="text-align:center; vertical-align: middle">{{$totalPending}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
