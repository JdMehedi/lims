<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@extends('layouts.admin')
@section('header-resources')
    @include('partials.datatable-css')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
@endsection
@section('content')
    @include('partials.messages')
    @include('partials.form-add-edit-css', ['viewMode' => 'off'])

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h5><strong><i class="fa fa-list"></i> Create New API</strong></h5>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="panel-body">
                    {!! Form::open(array('url' => 'visa-assistance/store','method' => 'post','id' => 'VisaAssistanceForm','enctype'=>'multipart/form-data',
                            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
                    <h3 class="text-center stepHeader">API Basic Info</h3>
                    <fieldset>

                        <div class="panel panel-info">
                            <div class="panel-heading"><strong class="">Test Add more</strong></div>
                            <div class="panel-body">

                                <table id="briefDescriptionTable"
                                       class="table table-striped table-bordered dt-responsive"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Employee name</th>
                                        <th>Experience</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="briefDescriptionRow" data-number="1">
                                        <td>
                                            {!! Form::text('emp_name[]', '', ['class' => 'form-control input-md']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('emp_experience[]', '', ['class' => 'form-control input-md']) !!}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                               onclick="addTableRow('briefDescriptionTable', 'briefDescriptionRow');"><i
                                                        class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </fieldset>

                    <h3 class="text-center stepHeader">Parameters</h3>
                    <fieldset>
                        <div id="docListDiv"></div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Operations</h3>
                    <fieldset>
                        <div id="docListDiv2"></div>
                    </fieldset>

                    <h3 class="stepHeader">Outputs</h3>
                    <fieldset>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <strong>Service Fee Payment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md required email']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', Auth::user()->user_mobile, ['class' => 'form-control input-md required phone_or_mobile']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', Auth::user()->contact_address .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_pay_amount', '', ['class' => 'form-control input-md', 'readonly']) !!}
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                <span class="label label-warning">Not Paid</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </fieldset>




                {!! Form::close() !!}<!-- /.form end -->

                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-js')
    @include('partials.form-add-edit-js', ['viewMode' => 'off'])
    <script src="{{ asset("assets/plugins/select2/js/select2.min.js") }}"></script>
    <script>
        $(document).ready(function () {


            var form = $("#VisaAssistanceForm").show();
            form.find('#submitForm').addClass('hidden');
            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    // Always allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                        return false;
                    }
                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex) {
                        // To remove error styles
                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    console.log(form.validate().errors());
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex) {
                    alert(currentIndex);
                    if (currentIndex == 2) {
                        form.find('#submitForm').removeClass('hidden');

                        $('#submitForm').on('click', function (e) {
                            form.validate().settings.ignore = ":disabled,:hidden";
                            //console.log(form.validate().errors()); // show hidden errors in last step
                            return form.valid();
                        });
                    } else {
                        form.find('#submitForm').addClass('hidden');
                    }
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    errorPlacement: function errorPlacement(error, element) {
                        element.before(error);
                    }
                }
            });

            var popupWindow = null;
            $('.finish').on('click', function (e) {
                if ($('#acceptTerms-2').is(":checked") && form.valid()) {
                    $('#acceptTerms-2').removeClass('error');
                    $('#acceptTerms-2').next('label').css('color', 'black');
                    $('body').css({"display": "none"});
                    popupWindow = window.open('<?php echo URL::to('/visa-assistance/preview'); ?>', 'Sample', '');
                } else {
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                }
            });

            $("select").change(function () {
                var id = $(this).attr("id");
                var val = $(this).val();
                $(this).find('option').removeAttr("selected");
                if (val != '') {
                    $(this).find('option[value="' + val + '"]').attr('selected', 'selected');
                    $(this).val(val);
                }
            });

            $("#mission_country_id").change(function () {
                var country_id = $('#mission_country_id').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/visa-assistance/get-embassy-by-country'); ?>",
                    data: {
                        country_id: country_id
                    },
                    success: function (response) {
                        var option;

                        if (country_id == '' || country_id == null) {
                            option += '<option value="">Select country first! </option>';
                        } else if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';

                            });
                        } else {
                            option += '<option value="">No embassy has been found for this country! </option>';
                        }
                        $("#high_commission_id").html(option);
                        $(self).next().hide();
                    }
                });
            });
        });


    </script>
@endsection
