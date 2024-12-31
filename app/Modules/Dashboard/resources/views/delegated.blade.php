@extends('layouts.admin')

@section('header-resources')
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h5>
                        <strong>
                            You are delegated to the following user. To take any action, please remove delegation
                            <i class="fa fa-rocket"></i>
                            <i class="fa fa-rocket"></i>
                        </strong>
                    </h5>
                </div>
                <div class="panel-body">
                    <h4 style="text-align: center;"><b>Delegation Information:</b></h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" width="50%">Delegated by user</th>
                                <th class="text-center" width="50%">Delegated to user</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><label>Name :</label> {{ $delegate_by_info->user_full_name }}</td>
                                <td><label>Name :</label> {{ $delegate_to_info->user_full_name }}</td>
                            </tr>
                            <tr>
                                <td><label>Designation :</label> {{ $delegate_by_info->designation }}</td>
                                <td><label>Designation :</label> {{ $delegate_to_info->designation }}</td>
                            </tr>
                            <tr>
                                <td><label>Email:</label> {{ $delegate_by_info->user_email }}</td>
                                <td><label>Email:</label> {{ $delegate_to_info->user_email }}</td>
                            </tr>
                            <tr>
                                <td><label>Mobile:</label> {{ $delegate_by_info->user_mobile }}</td>
                                <td><label>Mobile:</label> {{ $delegate_to_info->user_mobile }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><label>Delegated at:</label> {{ $delegate_to_info->delegated_at }}</td>
                            </tr>



                        </tbody>
                    </table>
                    <div style="text-align: center;">
                        <a class="remove-delegation btn btn-danger" href="{{ url('/users/remove-delegation/') }}"
                            onclick="return confirm('Are you sure?')">
                            <i class="fa fa-share-square-o"></i> Remove Delegation</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
