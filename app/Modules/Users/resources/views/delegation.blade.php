<?php

use App\Libraries\ACL;

$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')) {
    abort('400', 'You have no access right!. Contact with system admin for more information.');
} ?>


@extends('layouts.admin')

@section('header-resources')
@endsection

@section('content')

    {!! Session::has('success')
        ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>' .
            Session::get('success') .
            '</div>'
        : '' !!}
    {!! Session::has('error')
        ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>' .
            Session::get('error') .
            '</div>'
        : '' !!}

    {!! Form::open([
        'url' => '/users/store-delegation',
        'method' => 'patch',
        'class' => 'form-horizontal',
        'id' => 'delegation_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
    ]) !!}
    <div class="card card-info">
        <div class="card-header">
            <strong class="card-title"><i class="fa fa-external-link-square"></i> Delegation</strong>
        </div> <!-- /.panel-heading -->

        <div class="card-body">
            @if ($isDelegate != 0)
                <div class="card card-danger">
                    <div class="card-header">
                        <h5>
                            <strong>
                                <h3 class="card-title"> The user is delegated to the following user <i
                                        class="fa fa-rocket"></i><i class="fa fa-rocket"></i></h3>
                            </strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 style="text-align: center;"><b>Delegation Information:</b></h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50%">Delegated by user</th>
                                    <th class="text-center" width="50%">Delegated to user</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><label>Name :</label> {{ $delegate_by_info->user_full_name }}</td>
                                    <td><label>Name :</label> {{ $info->user_full_name }}</td>
                                </tr>
                                <tr>
                                    <td><label>Designation :</label> {{ $delegate_by_info->designation }}</td>
                                    <td><label>Designation :</label> {{ $info->designation }}</td>
                                </tr>
                                <tr>
                                    <td><label>Email:</label> {{ $delegate_by_info->user_email }}</td>
                                    <td><label>Email:</label> {{ $info->user_email }}</td>
                                </tr>
                                <tr>
                                    <td><label>Mobile:</label> {{ $delegate_by_info->user_mobile }}</td>
                                    <td><label>Mobile:</label> {{ $info->user_mobile }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><label>Delegated at:</label> {{ $info->delegated_at }}</td>
                                </tr>

                            </tbody>
                        </table>
                        <div style="text-align: center;">
                            <a class="remove-delegation btn btn-danger" onclick="return confirm('Are you sure?')"
                                href="{{ url('/users/remove-delegation/' . \App\Libraries\Encryption::encodeId($delegate_to_user_id)) }}">
                                <i class="fa fa-share-square-o"></i> Remove Delegation</a>
                        </div>
                    </div>
                </div>
            @else
                {!! Form::open([
                    'url' => '/users/store-delegation',
                    'method' => 'patch',
                    'class' => 'form-horizontal',
                    'id' => 'delegation_form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                ]) !!}
                <div class="form-group row {{ $errors->has('user_full_name') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right">User Name : </label>
                    <div class="col-md-4">
                        {{ $info->user_full_name }}
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('user_mobile') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right">Contact Number : </label>
                    <div class="col-md-4">
                        {{ $info->user_mobile }}
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('designation') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right">Designation : </label>
                    <div class="col-md-4">
                        {{ $info->designation }}
                    </div>
                </div>

                {{-- <div class="form-group has-feedback {{ $errors->has('desk_name') ? 'has-error' : ''}}"> --}}
                {{-- <label  class="col-md-3 text-right">Designation From : </label> --}}
                {{-- <div class="col-md-4"> --}}
                {{-- {{ $info->desk_name }} --}}
                {{-- </div> --}}
                {{-- </div> --}}
                <div class="form-group row {{ $errors->has('desk_id') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right">User Type : </label>
                    <div class="col-md-4">
                        {{-- {!! Form::hidden('user_id',$info->id) !!} --}}
                        {!! Form::hidden(
                            'user_id',
                            $info->id,
                            $attributes = ['class' => 'form-control', 'placeholder' => 'Enter your', 'id' => 'delegate_form_user_id'],
                        ) !!}
                        {{ $info->type_name }}
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('designation') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right required-star">Delegate To User Type :</label>
                    <div class="col-md-4">
                        {!! Form::select(
                            'designation',
                            $designation,
                            '',
                            $attributes = [
                                'class' => 'form-control required',
                                'onchange' => 'getUserDelegate()',
                                'placeholder' => 'Select one',
                                'id' => 'designation',
                            ],
                        ) !!}
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('delegated_user') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right required-star">Delegate To User :</label>

                    <div class="col-md-4">
                        {!! Form::select(
                            'delegated_user',
                            [],
                            '',
                            $attributes = ['class' => 'form-control required', 'placeholder' => 'Select user', 'id' => 'delegated_user'],
                        ) !!}
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('remarks') ? 'has-error' : '' }}">
                    <label class="col-md-3 text-right required-star">Remarks :</label>

                    <div class="col-md-4">
                        {!! Form::text(
                            'remarks',
                            null,
                            $attributes = [
                                'class' => 'form-control required',
                                'rows' => '5',
                                'placeholder' => 'Enter your Remarks',
                                'id' => 'remarks',
                            ],
                        ) !!}

                    </div>
                </div>
                <div class='clearfix'></div>
                <div class="col-md-2"></div>
                <div class="form-group col-md-2 m-auto">
                    @if (ACL::getAccsessRight('user', 'A'))
                        <button type="submit" class="btn btn-block btn-primary"><b>Delegate</b></button>
                    @endif
                </div>

                {!! Form::close() !!}
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
@endsection

@section('footer-script')
    <script>
        $(function() {
            $("#delegation_form").validate({
                errorPlacement: function() {
                    return false;
                }
            });
        });

        function getUserDelegate() {
            var _token = $('input[name="_token"]').val();
            var designation = $('#designation').val();
            var delegate_form_user_id = $('#delegate_form_user_id').val();

            $.ajax({
                url: '{{ url('users/admin/get-delegate-user-list') }}',
                type: 'post',
                data: {
                    _token: _token,
                    designation: designation,
                    delegate_form_user_id: delegate_form_user_id
                },
                dataType: 'json',
                success: function(response) {
                    html = '<option value="">Select One</option>';

                    $.each(response, function(index, value) {
                        html += '<option value="' + value.id + '" >' + value.user_full_name +
                            '</option>';
                    });
                    $('#delegated_user').html(html);
                },
                beforeSend: function(xhr) {
                    console.log('before send');
                },
                complete: function() {
                    //completed
                }
            });
        }
    </script>
@endsection
