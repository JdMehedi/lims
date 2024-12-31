<?php
$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')) {
    abort('400', 'You have no access right!. Contact with system admin for more information.');
}
?>

@extends('layouts.admin')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-info">
                <div class="card-header">
                    <h5 class="card-title"><strong>Assign User Parameters</strong></h5>
                </div>

                {!! Form::open(['url' => 'users/assign-parameters-save', 'method' => 'post']) !!}
                <div class="card-body">

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5><strong class="card-title"> User Information</strong></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class=" col-md-2">
                                    <span class="v_label">Email</span>
                                    <span class="pull-right">:</span>
                                </div>
                                <div class="col-md-10">
                                    {{ $user_info->user_email }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class=" col-md-2">
                                    <span class="v_label">Name</span>
                                    <span class="pull-right">:</span>
                                </div>
                                <div class="col-md-10">
                                    {{ $fullName }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class=" col-md-2">
                                    <span class="v_label">Moblie</span>
                                    <span class="pull-right">:</span>
                                </div>
                                <div class="col-md-10">
                                    {{ $user_info->user_mobile }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5><strong class="card-title"> Desk Assign</strong></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group col-md-10">

                                <input type="hidden" name="user_id" value="{{ Encryption::encodeId($user_info->id) }}">

                                {!! Form::label('assign_desk', 'Select desk:', ['class' => 'col-md-3 v_label']) !!}
                                <div class="col-md-9">
                                    <select name="assign_desk[]" class="city form-control limitedNumbSelect"
                                        data-placeholder="Select Desk to assign" style="width: 100%;" multiple="multiple">
                                        @foreach ($desk_list as $desk)
                                            @if (in_array($desk->id, $select_desk))
                                                <option value="{{ $desk->id }}" selected="true">{{ $desk->desk_name }}
                                                </option>
                                            @else
                                                <option value="{{ $desk->id }}">{{ $desk->desk_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {!! $errors->first('assign_desk', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                <!--
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h5><strong class="card-title"> Office Assign </strong></h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group col-md-10">
                                {!! Form::label('assign_park', 'Select Office:', ['class' => 'col-md-3 v_label']) !!}
                                <div class="col-md-9">
                                    <select name="assign_park[]" class="city form-control limitedNumbSelect2"
                                        data-placeholder="Select Desk to assign" style="width: 100%;" multiple="multiple">
                                        @foreach ($park_list as $park)
                                            @if (in_array($park->id, $select_park))
                                                <option value="{{ $park->id }}" selected="true">
                                                    {{ $park->park_name }}</option>
                                            @else
                                                <option value="{{ $park->id }}">{{ $park->park_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {!! $errors->first('assign_park', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                -->

                </div>
                <div class="card-footer">
                    <div class="float-left">
                        <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i class="fa fa-close"></i>
                            Close</a>
                    </div>
                    <div class="float-right">
                        @if (ACL::getAccsessRight('user', 'E'))
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>
                                Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            //Select2
            $(".limitedNumbSelect2").select2({
                maximumSelectionLength: 1
            });

            $(".limitedNumbSelect").select2({
                // maximumSelectionLength: 1
            });
        });
    </script>
@endsection
