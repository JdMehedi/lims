<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
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

    .\!font-normal {
        font-weight: normal !important;
    }

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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

    .wizard > .steps > ul > li {
        width: 50% !important;
    }

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    /*.wizard > .content > .body ul > li {*/
    /*    display: list-item !important;*/
    /*}*/

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="renewForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application for ITC License Amendment</h4>
            <div class="card-body">
                {!! Form::open([
                    'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                    'method' => 'post',
                    'class' => 'form-horizontal',
                    'id' => 'application_form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                ]) !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">

                </div>
                <h3>Basic Information</h3>
                <fieldset>
                    @include('common.subviews.licenseInfo', ['mode' => 'amendment', 'url' => 'itc-license-amendment/fetchAppData'])
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add', 'extra' => ['address2']])
                    {{-- Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'add', 'extra' => ['address2']])
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

                </fieldset>

                <h3>Attachment, Declaration & Submit</h3>
                <br>
                <fieldset>
                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Required Documents for Attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

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
                                            <label class=" !font-normal">
                                                Has any Application for License/ Registration of ITC been
                                                rejected before?
                                            </label>
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div id="if_declaration_q1_yes" style="display: none">
                                                <div class="form-group row" style="margin-top:10px;">
                                                    {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                                    <div
                                                        class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">
                                                        <div class="input-group date datetimepicker4"
                                                             id="application_date_picker"
                                                             data-target-input="nearest">
                                                            {!! Form::text('declaration_q1_application_date', '', ['class' => 'form-control', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#application_date_picker"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 20px;">
                                                    {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input','id' => 'declaration_q1_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Has any License/ Registration issued previously to the Applicant/ any
                                                Share Holder/ Partner been cancelled?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Do the Applicant/ any Share Holder/ Partner hold any other Operator
                                                Licenses from the Commission?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q3_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q3_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>

                                            <div class="form-group row" id="declaration_image" style="display:none;">
                                                {!! Form::label('attachment', 'Attachment', ['class' => 'col-md-2', 'style'=>'margin-top: 20px;', 'id' =>'attachment_label']) !!}
                                                <div class="col-md-8">
                                                    {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_image', 'accept'=>'application/pdf', 'onchange'=>"generateBase64String('if_declaration_q3_image','declaration_q3_images_base64')", 'style'=>'border: none; margin-top: 10px;','required'])}}
                                                    <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >
                                                </div>
                                            </div>
                                        </li>
                                        <li style="margin-top: 20px;" ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the instructions/terms and conditions, for the registration and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein. (Instructions for issuance of registration certificate for the operation of ITC are available at www.btrc.gov.bd.)</li>
                                        <li style="margin-top: 20px;" ><span class="i_we_dynamic">I/We</span> understand that this application if found incomplete in any respect and /or if found with conditional compliance shall be summarily rejected.</li>
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
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input required', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:5px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="float-left">
                    <a href="{{ url('client/scs-license-amendment/list/'. Encryption::encodeId(64)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    <button type="button" id="submitForm"
                            style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                            class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreview()">Submit
                    </button>
                </div>
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                        name="actionBtn"
                        id="save_as_draft">Save as Draft
                </button>
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
                                        thereafter without valid license. The late fees/ fines shall be recoverable under
                                        the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to
                                        submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will
                                        not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh
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

    @isset($companyInfo->office_district)
    getThanaByDistrictId('reg_office_district', {{ $companyInfo->office_district ?? '' }},
        'reg_office_thana', {{ $companyInfo->office_thana ?? '' }});
    @endisset

    $(document).ready(function () {


        var form = $("#application_form").show();
        // form.find('#save_as_draft').css('display','none');
        // form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                console.log(currentIndex,newIndex);
                if (newIndex == 1) {
                    // return true;

                    let errorStatus = serviceSectionValidation();

                    var total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }

                    // if (SectionValidation('.client-rendered-row input, .client-rendered-row select')) {
                    //     errorStatus = true;
                    // }

                    if (errorStatus) return false;

                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex < newIndex) {
                        return true;
                    }

                }

                if (currentIndex == 1) {

                }

                if (newIndex == 0) {
                    if (currentIndex > newIndex) {
                        return true;
                    }


                }

                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
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

                }
                    // Used to skip the "Warning" step if the user is old enough.
                    // if (currentIndex === 2) {
                    //     form.find('#submitForm').css('display', 'inline');
                    //     form.steps("next");
                // }
                else {
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
            let errorStatus = isCheckedAcceptTerms();
            if (SectionValidation('#docListDiv input')) {
                errorStatus = true;
            }

            if(declarationSectionValidation()){
                errorStatus = true;
            }

            if (errorStatus) return false;
            popupWindow = window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }

        attachmentLoad();

        function SetErrorInShareOfInputField() {
            $(".shareholder_share_of").each(function (index) {
                $(this).addClass('error');
            });
        }

        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function () {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: '01/01/' + (yyyy + 20),
            minDate: '01/01/1905'
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

    function mobile_no_validation(id) {
        var id = id;
        console.log(id)
        $("#" + id).on('keyup', function () {
            var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode;
            if (countryCode === "880") {
                var mobile = $("#" + id).val();
                var reg = /^0/;
                if (reg.test(mobile)) {
                    $("#" + id).val("");
                }
                if (mobile.length != 10) {
                    $("#" + id).addClass('error')
                }
            }
        });
    }
</script>

<script>
    var selectCountry = '';

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
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).on('change', '.shareholder_nationality', function () {
        let id = $(this).attr('id');
        let lastRowId = id.split('_')[2];

        if (this.value == 18) {
            $('#nidBlock_' + lastRowId).show();
            $('#shareholder_nid_' + lastRowId).addClass('required');

            $('#passportBlock_' + lastRowId).hide();
            $('#shareholder_passport_' + lastRowId).removeClass('required');

        } else {
            $('#nidBlock_' + lastRowId).hide();
            $('#shareholder_nid_' + lastRowId).removeClass('required');

            $('#passportBlock_' + lastRowId).show();
            $('#shareholder_passport_' + lastRowId).addClass('required');
        }
    });

    $(document).on('change', '#permanentSameAsRegisterdAddress', function (e) {
        if (this.checked === true) {
            let office_district = $("#reg_office_district").val();
            let office_upazilla_thana = $("#reg_office_thana").val();
            $("#noc_district").val(office_district);
            getThanaByDistrictId('noc_district', office_district, 'noc_thana', office_upazilla_thana.trim());
            $("#noc_address").val($("#reg_office_address").val());

        } else {
            $("#noc_thana").val('');
            $("#noc_address").val('');
            $("#noc_district").val('');
        }
    })

    // Declaration Section
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
            $('#declaration_image').css('display', 'flex');
        });


        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
            $('#declaration_image').css('display', 'none');
        });



        //get share value and number of share
        $("#shareholderRow").on("keyup", ".share-value", function(){
            calculateShareValue();
        });

        $("#shareholderRow").on('keyup', '.no-of-share', function(){
            calculateShareValue();
        });

        // total no. of share and total value calculation
        function calculateShareValue(){
            let sum_share = 0;
            $(".share-value").each(function(){
                sum_share = sum_share + parseInt(this.value);
            });
            $("#total_share_value").val(sum_share);
            // let sum_value = document.getElementById('total_no_of_share').value === '' ? 0 : parseInt(document.getElementById('total_no_of_share').value);
            let sum_value = 0;
            $(".no-of-share").each(function(){
                sum_value = sum_value + parseInt(this.value);
            });
            $("#total_no_of_share").val(sum_value);
        }


        //add shareholder row
        {{--        var rowId = 0;--}}
        {{--        $(".addShareholderRow").on('click', function () {--}}
        {{--            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);--}}
        {{--            $('#shareholderRow').append(--}}
        {{--                `<tr id="R_${lastRowId + 1}">--}}
        {{--<td>--}}
        {{--    <div class="card card-magenta border border-magenta">--}}
        {{--                                            <div class="card-header">--}}
        {{--                                                Shareholder/partner/proprietor Details Information--}}
        {{--                                                <span style="float: right; cursor: pointer;">--}}
        {{--                                                    <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button" style="float:right;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
        {{--                                                </span>--}}
        {{--                                            </div>--}}
        {{--                                            <div class="card-body" style="padding: 15px 25px;">--}}
        {{--    <div class="row client-rendered-row">--}}
        {{--        <div class="col-md-6">--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_name_${lastRowId + 1}', 'Name', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_name[${lastRowId}]', '', ['class' => 'form-control shareholder_name' , 'placeholder' => 'Enter Shareholder/partner/proprietor Name', 'id' => 'shareholder_name_${lastRowId + 1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_designation_${lastRowId + 1}', 'Designation', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_designation[${lastRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter The Name of Designation', 'id' => 'shareholder_designation_${lastRowId + 1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_email_${lastRowId + 1}', 'Email', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_email[${lastRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter The Email', 'id' => 'shareholder_email_${lastRowId + 1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_mobile_${lastRowId + 1}', 'Mobile Number', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_mobile[${lastRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${lastRowId + 1}','onkeyup' => 'mobile_no_validation(this.id)']) !!}--}}

        {{--                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_share_of_${lastRowId + 1}', '% of share', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::number('shareholder_share_of[${lastRowId}]', '', ['class' => 'form-control  shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${lastRowId + 1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('no_of_share_${lastRowId + 1}', 'No. of Share', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('no_of_share') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::number('no_of_share[${lastRowId + 1}]', '', ['class' => 'form-control no-of-share', 'placeholder' => 'Enter % of Share', 'id' => 'no_of_share_${lastRowId + 1}']) !!}--}}
        {{--                {!! $errors->first('no_of_share', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row" id="nidBlock_${lastRowId + 1}" style="display: none;">--}}
        {{--                {!! Form::label('shareholder_nid_${lastRowId + 1}', 'National ID No.', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_nid[${lastRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.'${lastRowId+1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            <div class="form-group row" id="passportBlock_${lastRowId + 1}" style="display: none;">--}}
        {{--                {!! Form::label('shareholder_passport_${lastRowId + 1}', 'Passport No.', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_passport[${lastRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${lastRowId+1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--        </div>--}}


        {{-- <div class="col-md-6">--}}
        {{--            <div class="form-group row" style="margin-bottom:0px!important;">--}}
        {{--                {!! Form::label('correspondent_photo_${lastRowId + 1}', 'Image', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}

        {{--                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
        {{--                        <div class="col-md-8">--}}
        {{--                            <input type="file" style="border: none; margin-bottom: 5px;" class="form-control input-sm correspondent_photo"--}}
        {{--                                   name="correspondent_photo[${lastRowId}]" id="correspondent_photo_${lastRowId + 1}" size="300x300"--}}
        {{--                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${lastRowId + 1}', 'correspondent_photo_base64_${lastRowId + 1}')" />--}}

        {{--                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
        {{--                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]--}}
        {{--                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
        {{--                                        </span>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-md-4">--}}
        {{--                            <label class="center-block image-upload" for="correspondent_photo_${lastRowId + 1}">--}}
        {{--                                <figure>--}}
        {{--                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
        {{--                                         src="{{asset('assets/images/demo-user.jpg') }}"--}}
        {{--                                         class="img-responsive img-thumbnail" id="correspondent_photo_preview_${lastRowId + 1}" />--}}
        {{--                                </figure>--}}
        {{--                                <input type="hidden" id="correspondent_photo_base64_${lastRowId + 1}" name="correspondent_photo_base64[${lastRowId}]" />--}}
        {{--                            </label>--}}
        {{--                        </div>--}}



        {{--                </div>--}}
        {{--            </div>--}}
        {{--            </div>--}}
        {{--            <div class="form-group row" style="margin-top:10px;">--}}
        {{--                {!! Form::label('shareholder_dob_${lastRowId + 1}', 'Date of Birth', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">--}}
        {{--                    --}}{{--                                        {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
        {{--                --}}{{--                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
        {{--                <div class="input-group date datetimepicker4" id="dob${lastRowId}" data-target-input="nearest">--}}
        {{--                        {!! Form::text('shareholder_dob[${lastRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${lastRowId + 1}']) !!}--}}
        {{--                <div class="input-group-append" data-target="#dob${lastRowId}" data-toggle="datetimepicker">--}}
        {{--                            <div class="input-group-text">--}}
        {{--                                <i class="fa fa-calendar"></i>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('shareholder_nationality_${lastRowId+1}', 'Nationality', ['class' => 'col-md-4']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::select('shareholder_nationality[${lastRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.'${lastRowId+1}']) !!}--}}
        {{--                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('share_value_${lastRowId+1}', 'Share Value', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('share_value') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::number('share_value[${lastRowId+1}]', '', ['class' => 'form-control share-value', 'placeholder' => 'Enter Share Value', 'id' => 'share_value_${lastRowId+1}']) !!}--}}
        {{--                {!! $errors->first('share_value', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--        </div>--}}
        {{--    </div>--}}
        {{--    </div>--}}
        {{--    </div>--}}
        {{--</td>--}}

        {{--</tr>`);--}}
        {{--            getHelpText();--}}

        {{--            $("#shareholderDataCount").val(lastRowId + 1);--}}
        {{--            var today = new Date();--}}
        {{--            var yyyy = today.getFullYear();--}}

        {{--            $('.datetimepicker4').datetimepicker({--}}
        {{--                format: 'DD-MMM-YYYY',--}}
        {{--                maxDate: 'now',--}}
        {{--                minDate: '01/01/' + (yyyy - 110),--}}
        {{--                ignoreReadonly: true,--}}
        {{--            });--}}
        {{--        });--}}


        {{--        $('#shareholderRow').on('click', '.shareholderRow', function () {--}}
        {{--            let prevDataCount = $("#shareholderDataCount").val();--}}

        {{--            var child = $(this).closest('tr').nextAll();--}}

        {{--            child.each(function () {--}}
        {{--                var id = $(this).attr('id');--}}
        {{--                var idx = $(this).children('.row-index').children('p');--}}
        {{--                var dig = parseInt(id.substring(1));--}}
        {{--                idx.html(`Row ${dig - 1}`);--}}
        {{--                $(this).attr('id', `R${dig - 1}`);--}}
        {{--            });--}}
        {{--            $(this).closest('tr').remove();--}}
        {{--            rowId--;--}}
        {{--            $("#shareholderDataCount").val(prevDataCount - 1);--}}
        {{--        });--}}

        //contact person row

        $(".addContactPersonRow").on('click', function () {
            let lastRowId = parseInt($('#contactPersonRow tr:last').attr('id').split('_')[2]);
            $('#contactPersonRow').append(
                `<tr class="client-rendered-row" id="cp_r_${lastRowId + 1}">
                    <td><div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Contact Person
                                                <span style="float: right; cursor: pointer;">
                                                     <button type="button" class="btn btn-danger btn-sm contactPersonRow cross-button" style="float:right;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_name_${lastRowId+1}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[${lastRowId}]', '', ['class' => 'form-control  contact_name required', 'placeholder' => 'Enter Name', 'id' => 'contact_name_${lastRowId+1}']) !!}
                {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_district_${lastRowId+1}', 'District', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_district[${lastRowId}]', $districts, '', ['class' => 'form-control  contact_district required', 'id' => 'contact_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('contact_district_".'${lastRowId+1}'."', this.value, 'contact_thana_".'${lastRowId+1}'."',0)"]) !!}
                {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_thana_${lastRowId+1}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_thana[${lastRowId}]', [], '', ['class' => 'form-control  contact_thana required', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_${lastRowId+1}']) !!}
                {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_address_${lastRowId+1}', 'Address', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[${lastRowId}]', '', ['class' => 'form-control  contact_person_address required', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_mobile_${lastRowId + 1}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_mobile[${lastRowId}]', '', ['class' => 'form-control  contact_mobile required bd_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${lastRowId + 1}','onkeyup' => 'mobile_no_validation(this.id)']) !!}
                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('contact_designation_${lastRowId + 1}', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                    {!! Form::text('contact_designation[${lastRowId + 1}]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter The Designation', 'id' => 'contact_designation_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_email_${lastRowId + 1}', 'Email Address', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[${lastRowId}]', '', ['class' => 'form-control contact_email', 'placeholder' => 'Enter Email', 'id' => 'contact_email_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_website_${lastRowId + 1}', 'Website', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_website[]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Enter Website', 'id' => 'contact_website_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</td>
</tr>`);
            getHelpText();
            $("#contactPersonDataCount").val(lastRowId + 1);
        });


        $('#contactPersonRow').on('click', '.contactPersonRow', function () {
            let prevDataCount = $("#contactPersonDataCount").val();

            var child = $(this).closest('tr').nextAll();

            child.each(function () {
                var id = $(this).attr('id');
                var idx = $(this).children('.row-index').children('p');
                var dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#contactPersonDataCount").val(prevDataCount - 1);
        });

        //contact person row

        $(".addAreaAddressRow").on('click', function () {
            let lastRowId = parseInt($('#areaAddressRow tr:last').attr('id').split('_')[2]);
            $('#areaAddressRow').append(
                `<tr class="client-rendered-row" id="aa_r_${lastRowId + 1}">
 <td>

                            <div class="card card-magenta border border-magenta">
                                                    <div class="card-header">
                                                        Area Address Information
                                                        <span style="float: right; cursor: pointer;">
                                                             <button type="button" class="btn btn-danger btn-sm areaAddressRow cross-button" style="float:right;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                     </span>
                                                    </div>
                                                    <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('proposal_district_${lastRowId+1}', 'District', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_district') ? 'has-error' : '' }}">
                                                    {!! Form::select('proposal_district[${lastRowId}]', $districts, '', ['class' => 'form-control proposal_district', 'id' => 'proposal_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('proposal_district_".'${lastRowId+1}'."', this.value, 'proposal_thana_".'${lastRowId+1}'."',0)"]) !!}
                {!! $errors->first('proposal_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_thana_${lastRowId+1}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select('proposal_thana[${lastRowId}]', [], '', ['class' => 'form-control proposal_thana', 'placeholder' => 'Select district at first', 'id' => 'proposal_thana_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
{!! Form::label('proposal_address_${lastRowId+1}', 'Address', ['class' => 'col-md-2']) !!}
                <div class="col-md-10 {{ $errors->has('proposal_address') ? 'has-error' : '' }}">
                                                    {!! Form::text('proposal_address[${lastRowId}]', '', ['class' => 'form-control proposal_address', 'placeholder' => 'Enter The Address', 'id' => 'proposal_address_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_no_of_seats_${lastRowId+1}', 'No.of Seats', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_no_of_seats') ? 'has-error' : '' }}">
                                                    {!! Form::number('proposal_no_of_seats[${lastRowId}]', '', ['class' => 'form-control', 'placeholder' => 'Enter The No. of Seats', 'id' => 'proposal_no_of_seats_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_no_of_seats', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('proposal_employee_${lastRowId+1}', 'Proposed of Employee', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                                                    {!! Form::number('proposal_employee[${lastRowId}]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Proposed of Employee', 'id' => 'proposal_employee_${lastRowId+1}']) !!}
                {!! $errors->first('proposal_employee', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('local_${lastRowId+1}', 'Local', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('local') ? 'has-error' : '' }}">
                    {!! Form::text('local[${lastRowId}]', '', ['class' => 'form-control local', 'placeholder' => 'Enter Local', 'id' => 'local_${lastRowId+1}']) !!}
                {!! $errors->first('local', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('expatriate_${lastRowId+1}', 'Expatriate', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('proposal_employee') ? 'has-error' : '' }}">
                    {!! Form::text('expatriate[${lastRowId}]', '', ['class' => 'form-control expatriate', 'placeholder' => 'Enter Expatriate', 'id' => 'expatriate_${lastRowId+1}']) !!}
                {!! $errors->first('expatriate', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
            </td>
</tr>`);
            getHelpText();
            $("#areaAddressDataCount").val(lastRowId + 1);
        });


        $('#areaAddressRow').on('click', '.areaAddressRow', function () {
            let prevDataCount = $("#areaAddressDataCount").val();

            var child = $(this).closest('tr').nextAll();

            child.each(function () {
                var id = $(this).attr('id');
                var idx = $(this).children('.row-index').children('p');
                var dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#areaAddressDataCount").val(prevDataCount - 1);
        });


    });
</script>
<script>
    $(document).ready(function () {

        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

        if (company_type == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (company_type == 3) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }


    });

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


    function generateBase64String(source, destination){
        const inputfile = document.getElementById(source);
        const file = inputfile.files[0];
        const result = URL.createObjectURL(file);
        document.getElementById(destination).value = result;
    }


    function openPreview() {
        let errorStatus = isCheckedAcceptTerms();
        if (SectionValidation('#docListDiv input')) {
            errorStatus = true;
        }

        if(declarationSectionValidation()){
            errorStatus = true;
        }

        if (errorStatus) return false;
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function serviceSectionValidation() {
        let errorStatus = false;
        if (!$("#callcenter").is(":checked")) {
            $("#service_section").addClass('error');
            errorStatus = true;
        } else {
            $("#service_section").removeClass('error');
        }

        if (!$("#domestic").is(":checked") && !$("#international").is(":checked")) {
            $("#service_type_section").addClass('error');
            errorStatus = true;
        } else {
            $("#service_type_section").removeClass('error');
        }
        return errorStatus;
    }

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
        console.log($("#declaration_q1_text").val(),$("#declaration_q1_application_date").val());

        if ($("#declaration_q1_yes").is(":checked") ) {
            if($("#declaration_q1_text").val() === ""){
                $("#declaration_q1_text").addClass('error');
                error_status = true;
            }else{
                $("#declaration_q1_text").removeClass('error');
            }

            if($("#declaration_q1_application_date").val() === "" ){
                $("#declaration_q1_application_date").addClass('error');
                error_status = true;
            }else{
                $("#declaration_q1_application_date").removeClass('error');
            }
        }

        if ($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === "") {
            $("#if_declaration_q2_yes").addClass('error');
            error_status = true;
        }else{
            $("#if_declaration_q2_yes").removeClass('error');
        }

        if ($("#declaration_q3_yes").is(":checked") && $("#if_declaration_q3_image").val() === "") {
            $("#if_declaration_q3_image").addClass('error');
            error_status = true;
        }else{
            $("#if_declaration_q3_image").removeClass('error');
        }

        if ($("#declaration_q3_yes").is(":checked") && $("#if_declaration_q3_yes").val() === "") {
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }else{
            $("#if_declaration_q3_yes").removeClass('error');
        }

        return error_status;
    }

    function SectionValidation(selector) {
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


</script>
