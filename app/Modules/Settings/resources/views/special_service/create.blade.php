<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) die('no access right!');
?>

@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.8.1/skins/content/default/content.min.css" />
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header" style="margin-top: -1px;">
                    <h5><strong> Create New Services </strong></h5>
                </div>

                {!! Form::open(array('url' => url('/settings/get-service/store'),'method' => 'post', 'class' => '', 'id' => 'app_guideline',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <!-- /.panel-heading -->
                <div class="card-body">

                   <div class="row " >

                   <div class="col-md-6">

                   <div class="form-group col-md-12  {{$errors->has('service_name') ? 'has-error' : ''}}" id="service_name">
                        {!! Form::label('service_name','Service Full Name:',['class'=>'col-md-6 control-label required-star'],'required') !!}
                        <div class="col-md-12">
                            <input class="form-control" name="service_name" type="text" id="service_name" required>
                            {!! $errors->first('service_name','<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12  {{$errors->has('group_name') ? 'has-error' : ''}}">
                        {!! Form::label('group_name','Group Name:',['class'=>'col-md-6 control-label required-star']) !!}
                        <div class="col-md-12">
                            {!! Form::text('group_name', null, ['class' => 'form-control required','maxlength'=>"3",'placeholder'=>'3 characters with no space']) !!}
                            {!! $errors->first('group_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                   </div>

                   <div class="col-md-6">

                   <div class="form-group col-md-12  {{$errors->has('service_short_name') ? 'has-error' : ''}}">
                        {!! Form::label('service_short_name','Service Short Name:',['class'=>'col-md-6 control-label required-star']) !!}
                        <div class="col-md-12">
                            {!! Form::text('service_short_name', null, ['class' => 'form-control required']) !!}
                            {!! $errors->first('service_short_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                   </div>

                   <div class="col-md-6">
                            <div class="form-group col-md-12 ">
                                {!! Form::label('active_status','Service Type: ',['class'=>'text-left required-star col-md-4']) !!}
                                        
                                <div class="row" style="margin-left: 20px;">
                                <div class="col-md-3" >
                                            <label class="form-check-label" for="issue">
                                                <input class="form-check-input required" name="services[]"
                                                       type="checkbox" id="issue" value="1">
                                                Issue
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check-label" for="renew">
                                                <input class="form-check-input required" name="services[]"
                                                       type="checkbox" id="renew" value="2">
                                                Renew
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check-label" for="ammendment">
                                                <input class="form-check-input required" name="services[]"
                                                       type="checkbox" id="ammendment" value="3">
                                                Ammendment
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check-label" for="surrender">
                                                <input class="form-check-input required" name="services[]"
                                                       type="checkbox" id="surrender" value="4">
                                                Surrender
                                            </label>
                                        </div>
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
                        <a href="{{ url('/settings/application-guideline') }}">
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
                {!! Form::close() !!}<!-- /.form end -->
            </div>
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{ asset('assets\scripts\jquery.validate.js') }}"></script>
    {{--<script src="{{ asset('assets\plugins\tinymce\tinymce.min.js') }}"></script>--}}
    <script src="{{asset('assets\plugins\tinymce\tinymce.min.js')}}"></script>
    <script>
        
        var group_names = '<?php echo( json_encode($group_names) ); ?>';
        group_names = JSON.parse('['+group_names+']');
        $(document).ready(function () {
            $("#app_guideline").validate({
                errorPlacement: function () {
                    return true;
                },
            });

            $('#group_name').on('keypress', function(e) {
            if (e.which == 32){
                
                return false;
            }
        });
            
            $('#group_name').change(function() {
                var name = $('#group_name').val();
                
                if(jQuery.inArray(name, group_names[0]) != -1) {
                    alert("duplicate group name exists");
                    $('#group_name').val('');
                } 
            });

           

        });


        
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
@endsection <!--- footer script--->
