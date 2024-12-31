<?php

use App\Libraries\ACL;

$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('no access right!');
}
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
                    <h3 class="card-title pt-2 pb-2"><i class="fa fa-list"></i> User List</h3>
                    <div class="card-tools">
                        @if (ACL::getAccsessRight('user', 'A'))
                            <a style="margin-left: 5px" class="" href="{{ url('/users/create-new-user') }}">
                                {!! Form::button('<i class="fa fa-plus"></i><b> ' . trans('Users::messages.new_user') . '</b>', [
                                    'type' => 'button',
                                    'class' => 'btn btn-default',
                                ]) !!}
                            </a>
                        @endif
                    </div>
                    <!-- /.card-tools -->
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list" class="table table-striped table-bordered dt-responsive " cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>{!! trans('Users::messages.user_name') !!}</th>
                                    <th>{!! trans('Users::messages.user_email') !!}</th>
                                    <th>{!! trans('Users::messages.user_type') !!}</th>
                                    {{-- <th>Company</th> --}}
                                    <th>{!! trans('Users::messages.status') !!}</th>
                                    <th>{!! trans('Users::messages.member_since') !!}</th>
                                    <th>{!! trans('Users::messages.action') !!}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-12 -->
    </div>
@endsection
<!--content section-->

@section('footer-script')
    @include('partials.datatable-js')
    <script>
        $(function() {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url('users/get-user-list') }}',
                    method: 'post',
                    data: function(d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [{
                        data: 'user_full_name',
                        name: 'user_full_name'
                    },
                    {
                        data: 'user_email',
                        name: 'user_email'
                    },
                    {
                        data: 'type_name',
                        name: 'type_name'
                    },
                    // {data: 'company_name', name: 'company_name'},
                    {
                        data: 'user_status',
                        name: 'user_status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection
<!--- footer-script--->
