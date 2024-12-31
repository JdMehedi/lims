<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) die('no access right!');
?>

@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.8.1/skins/content/default/content.min.css" />
{{--    @include('partials.messages')--}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header" style="margin-top: -1px;">
                    <h5><strong> Create user Mapping </strong></h5>
                </div>

{{--                {!! Form::open(array('url' => url('/settings/get-service/store'),'method' => 'post', 'class' => '', 'id' => 'app_guideline',--}}
{{--                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}--}}
                <!-- /.panel-heading -->
                <div class="card-body">

                   <div class="row " >

                   <div class="col-md-6">

                   <div class="form-group col-md-12" id="service_name">
                        {!! Form::label('lims_user','D-Nothi User:',['class'=>'col-md-6 control-label required-star'],'required') !!}
                        <div class="col-md-12">
                            {!! Form::select('l_id',$users, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('nothi_user','LIMS User:',['class'=>'col-md-6 control-label required-star']) !!}
                        <div class="col-md-12">
                            {!! Form::select('id', $users, '', ['class' => 'form-control']) !!}
                        </div>
                    </div>

                   </div>

                   <div class="col-md-6">

                   <div class="form-group col-md-12">
                        <div class="col-md-12">
                        </div>
                    </div>

                   </div>

                   </div>







                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->

                <div class="card-footer">
                    <div class="float-left">
                        <a href="">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="float-right">
                        @if(ACL::getAccsessRight('settings','A'))
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Create
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{ asset('assets\scripts\jquery.validate.js') }}"></script>
    {{--<script src="{{ asset('assets\plugins\tinymce\tinymce.min.js') }}"></script>--}}
    <script src="{{asset('assets\plugins\tinymce\tinymce.min.js')}}"></script>
    <script>

    </script>
@endsection <!--- footer script--->
