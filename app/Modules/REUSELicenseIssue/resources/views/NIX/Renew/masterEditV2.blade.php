<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }

    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
    }

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 50% !important;
    }

    .card-header::after {
        display: none;
    }

    .d\:flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    mr-2 {
        margin-right: 0.5rem;
    }

    .wizard {
        overflow: visible;
    }

    .wizard > .content {
        overflow: visible;
    }

    .wizard > .actions {
        width: 70% !important;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }

    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }

    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }

    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    /*.wizard > .actions {*/
    /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

    /*form label {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*.table>thead:first-child>tr:first-child>th {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*td {*/
    /*    font-size: 14px;*/
    /*}*/

    .signature-upload-input {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
        background-color: blue;
    }

    .sign_div {
        position: relative;
    }

    .signature-upload {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        padding: 5px 10px;
        font-size: 1rem;
        text-align: center;
        color: #000;
        background-color: #F5FAFE;
        border-radius: 0 !important;
        border: 0;
        height: 160px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }

    .custom_error {
        outline: 1px dashed red;
    }

    .border-header-box {
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }

    .border-header-txt {
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    .email {
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard > .actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard > .content > .body label {
            margin-top: .5em;
            margin-bottom: 0;
        }

        .tabInput {
            width: 120px;
        }

        .tabInput_sm {
            width: 75px;
        }

        .tabInputDate {
            width: 150px;
        }

        .table_responsive {
            overflow-x: auto;
        }

        /*.bootstrap-datetimepicker-widget {*/
        /*    position: relative !important;*/
        /*    top: 0 !important;*/
        /*}*/
        .prevMob {
            margin-top: 45px;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

    /*.wizard > .steps > ul > li {*/
    /*    width: 33.2% !important;*/
    /*}*/

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="renewForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <div class="card-header d:flex justify-between">
                <h4 class="card-header">Application for National Internet Exchange (NIX) Renew</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye mr-2"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">
                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                </div>
                <h3>Basic Information</h3>
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'renew-form-edit'])
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'renew', 'selected' => 1])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.applicantProfile', ['mode' => 'renew', 'selected' => 1])

                    {{-- Name of Authorized Signatory and Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'renew', 'selected' => 1])

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'renew', 'selected' => 1])
                    @if($appInfo->status_id == 5)
                        @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'shortfall'])
                    @endif

                </fieldset>

                <h3>Attachment & Declaration & Submit</h3>
                <fieldset>

                    {{-- Necessary attachment --}}
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'edit'])


                    {{-- Declaration --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Declaration Has any Application for License of ISP been rejected before?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q1" >
                                                {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px; {{ ($appInfo->declaration_q1 == 'Yes') ? 'display:block;' :'display:none;' }} " id="if_declaration_q1_yes">
                                                {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input input_disabled', 'id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                            </div>

                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any License of ISP issued previously to the Applicant/any Share
                                                Holder/Partner been cancelled?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px; {{($appInfo->declaration_q2 == 'Yes') ? 'display:block;' :'display:none;'}} " id="if_declaration_q2_yes">
                                                {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input input_disabled', 'id'=>'declaration_q2_text','placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                            </div>

                                        </li>

                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator
                                                Licenses from the Commission?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px; {{ ($appInfo->declaration_q3 == 'Yes') ? 'display:block;' : 'display:none;' }} " id="if_declaration_q3_yes">
                                                {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input required input_disabled', 'id'=>'declaration_q3_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>

                                        </li>
                                        <li><span class="i_we_dynamic">I/We</span> hereby certify that <span
                                                class="i_we_dynamic">I/We</span> have carefully read the
                                            guidelines/terms and conditions, for the license and <span
                                                class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                            conditions therein.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                            certify that <span class="i_we_dynamic">I/We</span> have carefully read the
                                            section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span
                                                class="i_we_dynamic">I/We</span> are not disqualified from obtaining the
                                            license.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that any information furnished in this application are found fake or false
                                            or this application form is not duly filled up, the Commission, at any time
                                            without any reason whatsoever, may reject the whole application.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that if at any time any information furnished for obtaining the license is
                                            found incorrect then the license if granted on the basis of such application
                                            shall deemed to be cancelled and shall be liable for action as per
                                            Bangladesh Telecommunication Regulation Act, 2001.
                                        </li>
                                    </ol>
                                </div>
                            </div>


                        </div>
                    </div>

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">

                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                    {{--                                    <a href="#" data-toggle="modal" data-target="#terms_and_conditions_modal">Terms and Conditions.</a>--}}
                                    {{--                                    <a href="#" data-toggle="modal" data-target="#otp_modal" class="btn btn-info btn-block btn-md"><strong><i--}}
                                    {{--                                                class=" fa fa-lock "></i> Login with--}}
                                    {{--                                            OTP</strong></a>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                </fieldset>


                <div class="float-left">
                    <a href="{{ url('client/nix-license-renew/list/'. Encryption::encodeId(10)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    <button type="button" id="submitForm"
                            style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                            class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreview()">{{ ($appInfo->status_id == 5)? 'Re-submit':'Submit'  }}
                    </button>
                </div>
                @if(!in_array($appInfo->status_id,[5]))
                    <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                            name="actionBtn"
                            id="save_as_draft">Save as Draft
                    </button>
                @endif
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">

        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close"
                                                                                 data-dismiss="modal"
                                                                                 style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the
                                        expiration of duration of its license or else the license shall be cancelled as
                                        per law and penal action shall follow, if the licensee continues its business
                                        thereafter without valid license. The late fees/fines shall be recoverable under
                                        the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to
                                        submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will
                                        not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh
                                        Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>
    var selectCountry = '';

    $(document).ready(function () {

        getHelpText('nix-license-renew');

        // Registered Office Address
        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset
        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset

        // Applicant Profile
        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
        @endisset

        // Contact Information
        @isset($contact_person)
        @foreach($contact_person as $index => $person)
        @if(empty($person->district) || empty($person->upazila))
        @continue
        @endif
        getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},
            'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});
        @endforeach
        @endisset

        var form = $("#application_form").show();
        // form.find('#save_as_draft').css('display','none');
        // form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                if (newIndex == 1) {
                    //return true;

                    var total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        return false;
                    }
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }

                }

                if (newIndex == 2) {
                    if (currentIndex > newIndex) {
                        return true;
                    }
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

                if(currentIndex > newIndex) return true ;

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {

                if (currentIndex > 0) {
                    form.find('#submitForm').css('display', 'none');
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex === 1) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");

                }else {
                    form.find('#submitForm').css('display', 'none');
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('.previous').removeClass('prevMob');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        var popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")) {
                $('#accaccept_termseptTerms').removeClass('error');
                $('#accept_terms').next('label').css('color', 'black');
                $('body').css({"display": "none"});
                $("form").submit();
            } else {
                $('#acceptTerms').addClass('error');
                return false;
            }
        });

        $('.finish').on('click', function (e) {
            let errorStatus = sectionValidation("#docListDiv input");

            if (declarationSectionValidation())  errorStatus = true;

            if (isCheckedAcceptTerms()) errorStatus = true;

            if (errorStatus) return false;
            popupWindow = window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;

            $("#doc_type_key").val(key);
            loadApplicationDocs('docListDiv', null);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function () {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            // maxDate: 'now',
            // minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            // maxDate: '01/01/' + (yyyy + 20),
            // minDate: '01/01/1905'
        })

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{-- initail -input mobile plugin script start --}}
        $("#company_office_mobile").intlTelInput({
            hiddenInput: "company_office_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#company_factory_mobile").intlTelInput({
            hiddenInput: "company_factory_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#company_ceo_mobile").intlTelInput({
            hiddenInput: "company_ceo_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#auth_person_mobile").intlTelInput({
            hiddenInput: "auth_person_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        {{-- initail -input mobile plugin script end --}}

    })
</script>

<script>
    var selectCountry = '';
    $(document).on('keydown', '#local_wc_ivst_ccy', function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#usd_exchange_rate').focus();
        }
    })
    $(document).on('keydown', '#usd_exchange_rate', function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#ceo_taka_invest').focus();
        }
    })
    $(document).on('change', '.companyInfoChange', function (e) {
        $('#same_address').trigger('change');
    })
    $(document).on('blur', '.companyInfoInput', function (e) {
        $('#same_address').trigger('change');
    })
    $(document).ready(function () {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function () {

            if (this.checked === false) {
                $('#company_factory_div').removeClass('hidden');
                this.checked = false;
                // $('#company_factory_division_id').val($('#company_office_division_id').val())
                // getDistrictByDivisionId('company_factory_division_id', $('#company_office_division_id').val(), 'company_factory_district_id',$('#company_office_district_id').val());
                // getThanaByDistrictId('company_factory_district_id', $('#company_office_district_id').val(), 'company_factory_thana_id', $('#company_office_thana_id').val());
                // // $('#company_factory_thana_id').val($('#company_office_thana_id').val())
                // $('#company_factory_postCode').val($('#company_office_postCode').val())
                // $('#company_factory_address').val($('#company_office_address').val())
                // $('#company_factory_email').val($('#company_office_email').val())
                // $('#company_factory_mobile').val($('#company_office_mobile').val())
                //
                $("#company_factory_division_id").val("");
                $("#company_factory_district_id").val("");
                $("#company_factory_thana_id").val("");
                $("#company_factory_postCode").val("");
                $("#company_factory_address").val("");
                $("#company_factory_email").val("");
                $("#company_factory_mobile").val("");
            } else {
                this.checked = true;
                $('#company_factory_div').addClass('hidden');
                $("#company_factory_division_id").val("").removeClass('error');
                $("#company_factory_district_id").val("").removeClass('error');
                $("#company_factory_thana_id").val("").removeClass('error');
                $("#company_factory_postCode").val("").removeClass('error');
                $("#company_factory_address").val("").removeClass('error');
                $("#company_factory_email").val("").removeClass('error');
                $("#company_factory_mobile").val("").removeClass('error');
            }

        });

        // $("#same_address").trigger('change');


        $("#total_investment").trigger('change');

        $("#industrial_sector_id").change(function () {
            var industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function (response) {

                    var option =
                        '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {

                            option += '<option value="' + id + '">' + value +
                                '</option>';
                        });
                    }
                    $("#industrial_sub_sector_id").html(option);
                    @if (isset($companyInfo->ins_sub_sector_id))
                    $("#industrial_sub_sector_id").val(
                        "{{ $companyInfo->ins_sub_sector_id }}").change();
                    @endif
                    $(self).next().hide();
                }
            });
        });


        LoadListOfDirectors();
    })
</script>
<script>
    function openModal(btn) {
        //e.preventDefault();
        var this_action = btn.getAttribute('data-action');
        if (this_action != '') {
            $.get(this_action, function (data, success) {
                if (success === 'success') {
                    $('#myModal .load_modal').html(data);
                } else {
                    $('#myModal .load_modal').html('Unknown Error!');
                }
                $('#myModal').modal('show', {
                    backdrop: 'static'
                });
            });
        }
    }

    $(document).ready(function () {
        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url('client/company-profile/load-listof-directors-session') }}",
            type: "POST",
            data: {
                {{-- app_id: "{{ Encryption::encodeId($appInfo->id) }}", --}}
                    {{-- process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}", --}}
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                var html = '';
                if (response.responseCode == 1) {

                    var edit_url = "{{ url('/client/company-profile/edit-director') }}";
                    var delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                    var count = 1;
                    $.each(response.data, function (id, value) {
                        var sl = count++;
                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' +
                            edit_url + '/' + id +
                            '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                        if (sl != 1) {
                            html += '<a data-action="' + delete_url + '/' + id +
                                '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if (response.ceoInfo != null) {
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                        if (response.ceoInfo.nationality == 'Bangladeshi') {
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if (response.ceoInfo.identity_type == 'passport') {
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nationality").val('');
                            $("#company_ceo_nid").val('');
                        } else {
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    } else {
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorList tbody').html(html);
            }
        });
    }

    //confirm delete alert
    function ConfirmDelete(btn) {
        var sure_delete = confirm("Are you sure you want to delete?");
        if (sure_delete) {
            var url = btn.getAttribute('data-action');
            $.ajax({
                url: url,
                type: "get",
                success: function (response) {
                    if (response.responseCode == 1) {
                        toastr.success(response.msg);
                    }

                    LoadListOfDirectors();
                }
            });

        } else {
            return false;
        }
    }
</script>


<script type="text/javascript">
    $(function () {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).ready(function () {


        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
        });
        $("#declaration_q1_no").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'none');
        });

        $("#declaration_q2_yes").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'inline');
        });
        $("#declaration_q2_no").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'none');
        });

        $("#declaration_q3_yes").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'inline');
        });
        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
        });


    });
</script>
<script>
    $(document).ready(function () {


        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#passportBlock_' + lastRowId).hide();

            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#passportBlock_' + lastRowId).show();
            }
        });

        $('#type_of_isp_licensese').on('change', function () {
            if (this.value == 1 || this.value == "") {
                $('#division').css('display', 'none');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
            }
            if (this.value == 2) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
            }

            if (this.value == 3) {
                $('#division').css('display', 'none');
                $('#district').css('display', 'inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">' +
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}' +
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">' +
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}' +
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}' +
                    '</div>' +
                    '</div>');
                $('#thana').css('display', 'none');
            }

            if (this.value == 4) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">' +
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}' +
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">' +
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}' +
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}' +
                    '</div>' +
                    '</div>');
                $('#thana').css('display', 'inline');
            }
        });

        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

        if (company_type == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (company_type == 1) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }


        $('.add_row').click(function () {
            var btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                }
            });
        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });


        $("#type_of_isp_licensese").change(function () {
            {{--var total_investment = $('#total_investment').val();--}}
            {{--var vat_percentage = parseFloat('{{ $vat_percentage }}');--}}

            let oss_fee = 0;
            let vat = 0;

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",
                data: {
                    process_type_id: {{ $process_type_id }},
                    payment_type: 1,
                    license_type: $(this).val()
                },
                success: function (response) {
                    /*$("#industrial_category_id").val(response.data);
                    if (response.data !== "") {
                        $("#industrial_category_id").find("[value!='" + response.data +
                            "']").prop("disabled", true);
                        $("#industrial_category_id").find("[value='" + response.data + "']")
                            .prop("disabled", false);
                    }*/
                    if (response.responseCode == -1) {
                        alert(response.msg);
                        return false;
                    }

                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function () {
                    {{--console.log({{ $process_type_id }});--}}
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0
                    };
                    loadPaymentPanel('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        });
    });

    function openPreview() {
        let errorStatus = sectionValidation("#docListDiv input");

        if (declarationSectionValidation())  errorStatus = true;

        if (isCheckedAcceptTerms()) errorStatus = true;

        if (errorStatus) return false;
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function imagePreview(input, img_preview_id) {

        if (input.files && input.files[0]) {
            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                input.value = '';
                alert('Image format is not valid. Only PNG or JPEG or JPG type images are allowed.');
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(img_preview_id).setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById(base64_value_target).value = '';
            document.getElementById(img_preview_id).setAttribute('src', '<?php echo e( url( 'assets/images/no_image.png' ) ); ?>');
        }
    }

    /*  //total share of percentage
      $(document).ready(function () {
          $(document.body).find('#application_form').on('submit', function () {
              var percent_of_share_total = 0;
              $(".shareholder_share_of").each(function (i, em) {
                  percent_of_share_total += parseInt($(em).val());
              })
              if (percent_of_share_total < 100 || percent_of_share_total > 100) {
                  new swal({
                      type: 'error',
                      text: 'The value of the "% of share field" should be a total of 100.',
                  });
                  return false;
              }
          });
      }); */


    function declarationSectionValidation() {
        let error_status = false;

        if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
            if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                    error_status = false;
                } else {
                    new swal({
                        type: 'error',
                        text: 'Please answer the Declaration section all question.',
                    });
                    error_status = true;
                }
            } else {
                new swal({
                    type: 'error',
                    text: 'Please answer the Declaration section all question.',
                });
                error_status = true;
            }
        } else {
            new swal({
                type: 'error',
                text: 'Please answer the Declaration section all question.',
            });
            error_status = true;
        }

        if ($("#declaration_q1_yes").is(":checked") && $("#declaration_q1_text").val() === "" ) {
            $("#declaration_q1_text").addClass('error');
            error_status = true;
        }else{
            $("#declaration_q1_text").removeClass('error');
        }

        if ($("#declaration_q2_yes").is(":checked") && $("#declaration_q2_text").val() === "") {
            $("#declaration_q2_text").addClass('error');
            error_status = true;
        }else{
            $("#declaration_q2_text").removeClass('error');
        }

        if ($("#declaration_q3_yes").is(":checked") && $("#declaration_q3_text").val() === "") {
            $("#declaration_q3_text").addClass('error');
            error_status = true;
        }else{
            $("#declaration_q3_text").removeClass('error');
        }

        return error_status;
    }

    function sectionValidation(selector) {
        const requiredClientFields = document.querySelectorAll(selector);
        let errorStatus = false;
        for (const elem of requiredClientFields) {
            if (elem.classList.contains('required') && !elem.value) {
                elem.classList.add('error');
                errorStatus = true;
            }
        }
        return errorStatus;
    }

    function isCheckedAcceptTerms() {
        let errorStatus = false;
        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            errorStatus = true;
        } else {
            $('#accept_terms').removeClass('error');
            errorStatus = false;
        }
        return errorStatus;
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }


</script>
