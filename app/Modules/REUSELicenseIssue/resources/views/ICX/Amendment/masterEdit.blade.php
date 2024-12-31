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

    .p-r-0 {
        padding-right: 0;
    }

    .m-l-auto {
        margin-left: auto;
    }

    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
    }

    .card-magenta:not(.card-outline) > .card-header {
        display: inherit;
    }

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
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

    /*.wizard>.steps>ul>li {*/
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
    <div class="col-md-12 col-lg-12" id="inputForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <div class="card-header d:flex justify-between">
            <h4 class="card-header">Application for ICX License Amendment</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye" style="margin-right: 5px;"></i> Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
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
                <div style="display: none;" id="pcsubmitadd"></div>
                {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                {!! Form::hidden('license_no', $appInfo->license_no, ['class' => 'form-control input-md required', 'id' => 'license_no']) !!}

                {!! Form::hidden('issue_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}

                {{--Basic Information--}}
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', [ 'mode' => 'amendment-form-edit'])
                  
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'amendment'])

                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'amendment'])

                    @includeIf('common.subviews.ContactPerson', ['mode' => 'amendment'])
                    {{-- @includeIf('common.subviews.ContactPerson', ['mode' => 'amendment', 'selected' => 1]) --}}

                    @includeIf('common.subviews.Shareholder', ['mode' => 'amendment'])
                </fieldset>

                {{--Attachment Declration--}}
                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{--Required Documents--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header" id="reqDoc">
                            Required Documents for attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

                    {{--Declaration--}}
                    <div class="card card-magntea border border-magenta mt-4">
                        <div class="card-header d-flex justify-content-between areaAddress">
                            <div>Declaration</div>
                            <div>
                                <label>
                                    <input type="checkbox" id="declarations"/>
                                    EDIT
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="declarationFields" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class="required-star !font-normal input_disabled">
                                                Has any Application for License of VSAT-HUB Operator been rejected
                                                before?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? 'selected' : '' , ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No',$appInfo->declaration_q1 == 'No' ? 'selected' : '', ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px; display:none;" id="if_declaration_q1_yes">
                                                {{ Form::textarea('declaration_q1_text',$appInfo->declaration_q1_text, array('class' =>'form-control input input_disabled', 'id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class="required-star !font-normal input_disabled">
                                                Has any Application for License of VSAT-HUB Operator been rejected
                                                before?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? 'selected' : '', ['class'=>'form-check-input input_disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none;" id="if_declaration_q2_yes">
                                                {{ Form::textarea('declaration_q2_text', $appInfo->declaration_q2_text, array('class' =>'form-control input input_disabled', 'id'=>'declaration_q2_text','placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>

                                        <li>
                                            <label class="required-star !font-normal input_disabled">
                                                Do the Applicant(s) any Share Holder(s) Partners) hold any other
                                                Operator from the Commision?
                                            </label>
                                            <div style="margin: 10px 0;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes',$appInfo->declaration_q3 == 'Yes' ? 'selected' : '', ['class'=>'form-check-input input_disabled', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label input_disabled','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No',$appInfo->declaration_q3 == 'No' ? 'selected' : '', ['class'=>'form-check-input input_disabled ', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label input_disabled','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none;" id="if_declaration_q3_yes">
                                                @php $declarationRequireClass = !empty($appInfo->declaration_q3_doc) ? 'declarationFile': 'required' @endphp
                                                {{ Form::file('declaration_q3_images',['class'=> "form-control input input_disabled $declarationRequireClass",'id'=>'declaration_q3_images','style'=>'margin-bottom: 20px;'])}}
                                                @isset($appInfo->declaration_q3_doc)
                                                    @php $fileNameArray = explode("/",$appInfo->declaration_q3_doc) @endphp
                                                    <a href="{{ asset($appInfo->declaration_q3_doc)}}"
                                                       data-image-preview="{{ end($fileNameArray) }}"
                                                       id="declaration_q3_images_preview" target="_blank"
                                                       class="btn btn-sm btn-danger" style="margin-top: 5px">Open</a>
                                                @endisset
                                            </div>

                                        </li>
                                        <li><span class="i_we_dynamic">I/We</span> hereby certify that <span
                                                class="i_we_dynamic">I/We</span> have carefully read the
                                            guidelines/terms and conditions, for the license and <span
                                                class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                            conditions therein. (Terms and Conditions of License Guidelines for VSAT-HUB
                                            Operator are available at www.btrc.gov.bd)
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                            certify that <span class="i_we_dynamic">I/We</span> have carefully read the
                                            section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span
                                                class="i_we_dynamic">I/We</span> are not disqualified from obtaining the
                                            license.
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                            that this application if found incomplete in any respect and or if found
                                            with conditional compliance shall be summarily rejected.
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
                </fieldset>

                {{--Payment and Submit--}}
                <h3> {{ ($appInfo->status_id == 5 )? 'Re-Submit' : 'Payment & Submit' }}</h3>
                <fieldset>
                    {{-- Service Fee Payment --}}
                    <div id="payment_panel"></div>

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms','required']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/vsat-license-ammendment/list/'. Encryption::encodeId(15)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                        </button>
                    @endif

                    @if($appInfo->status_id == 5)
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md" onclick="ReSubmitForm()"
                                value="Re-submit" name="actionBtn">Re-submit
                        </button>
                    @endif
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

    let company_type = "{{$appInfo->org_type}}";
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


    @isset($appInfo->org_district)
    getThanaByDistrictId('reg_office_district', {{ $appInfo->org_district ?? '' }},
        'reg_office_thana', {{ $appInfo->org_upazila ?? '' }});
    @endisset

    @isset($appInfo->org_permanent_district)
    getThanaByDistrictId('op_office_thana', {{ $appInfo->org_permanent_district ?? '' }},
        'op_office_district', {{ $appInfo->org_permanent_upazila ?? '' }});
    @endisset

    @isset($appInfo->applicant_district)
    getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
        'applicant_upazila_thana', {{ $appInfo->applicant_upazila ?? '' }});
    @endisset

    @foreach($contact_person as $key => $contact_person)
    @isset($contact_person->district)
    getThanaByDistrictId(`contact_district_{{$key+1}}`, {{ $contact_person->district ?? '' }},
        `contact_thana_{{$key+1}}`, {{ $contact_person->upazila ?? '' }});
    @endisset
    @endforeach

    @if($appInfo->declaration_q1 == 'Yes')
    $('#if_declaration_q1_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q2 == 'Yes')
    $('#if_declaration_q2_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q3 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif

    let selectCountry = '';
    $(document).ready(function () {
        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                let errorStatus = false;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        // alert("The value of the '% of share field' should be a total of 100.");
                        new swal({
                            type: 'error',
                            text: 'The value of the % of share field should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }
                }
                if (newIndex == 3) {
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {

                            } else {
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        } else {
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
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
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if (!form.valid()) errorStatus = true;
                if (errorStatus) return false;
                return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex === 1) {
                    // form.find('#submitForm').css('display', 'inline');
                    // form.steps("next");
                }else{
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('.previous').removeClass('prevMob');
                }

                if(currentIndex === 2){
                    $('#submitForm').css('display', 'inline');
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

        let popupWindow = null;
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
            if (isCheckedAcceptTerms()) return false;
            popupWindow = window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
        });


        function attachmentLoad() {
            let reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            let company_type_id = parseInt($("#company_type_id").val()); //order 2
            let industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            let investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            let key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');

        let today = new Date();
        let yyyy = today.getFullYear();

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


        $(function () {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

        //form & field operation
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

        // add contact person row
        {{--        $(".addContactPersonRow").on('click', function () {--}}
        {{--            let lastRowId = $('#contactPersonRow .card:last').attr('id').split('_')[1];--}}
        {{--            let updateRowId = parseInt(lastRowId) + 1;--}}
        {{--            console.log(lastRowId);--}}
        {{--            // return false;--}}
        {{--            $("#contactPersonRow").append(`--}}
        {{--            <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_${updateRowId}">--}}
        {{--                                    <div class="card-header">--}}
        {{--                                        Contact Person Information--}}
        {{--                                    <span style="float: right; cursor: pointer;" class="m-l-auto">--}}
        {{--                                        <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
        {{--                                    </span>--}}

        {{--                                    </div>--}}
        {{--            <div class="card-body" style="padding: 15px 25px;">--}}
        {{--<div class="card-body" style="padding: 5px 0px 0px;">--}}
        {{--                <div class="row">--}}
        {{--                    <div class="col-md-6">--}}
        {{--                        <div class="form-group row">--}}
        {{--                            {!! Form::label('contact_name_${updateRowId}', 'Name', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">--}}
        {{--                                {!! Form::text('contact_person_name[${updateRowId}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_name_${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--    <div class="col-md-6">--}}
        {{--<div class="form-group row">--}}
        {{--{!! Form::label('contact_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_designation_${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}

        {{--<div class="row">--}}
        {{--    <div class="col-md-6">--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('contact_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::text('contact_mobile[${updateRowId}]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--    <div class="col-md-6">--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('contact_email_${updateRowId}', 'Email', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_email_${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}


        {{--<div class="row">--}}
        {{--    <div class="col-md-6">--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('contact_district_${updateRowId}', 'District', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_'.'${updateRowId}', 'onchange' => 'getThanaByDistrictId("contact_district_${updateRowId}",this.value, "contact_thana_${updateRowId}",0)']) !!}--}}
        {{--            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--        <div class="col-md-6">--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('contact_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.'${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}

        {{--<div class="row">--}}
        {{--    <div class="col-md-6">--}}
        {{--        <div class="form-group row">--}}
        {{--            {!! Form::label('contact_person_address_${updateRowId}', 'Address', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">--}}
        {{--                                                        {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${updateRowId}']) !!}--}}
        {{--            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--    <div class="col-md-6">--}}
        {{--        <div class="form-group row" style="margin-bottom:0px!important;">--}}
        {{--            {!! Form::label('contact_photo_edit_${updateRowId}', 'Photograph', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}
        {{--                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
        {{--                    <div class="col-md-8">--}}
        {{--                        <input type="file"--}}
        {{--                                style="border:none; margin-bottom: 5px;"--}}
        {{--                               class="form-control input-sm contact_photo {{ !empty(Auth::user()->user_pic) ? '' : '' }}"--}}
        {{--                               name='contact_photo[${updateRowId}]' id="contact_photo_edit_${updateRowId}"--}}
        {{--                               onchange="imageUploadWithCroppingAndDetect(this, 'contact_photo_preview_${updateRowId}', 'contact_photo_base64_${updateRowId}')"--}}
        {{--                               size="300x300" />--}}
        {{--                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
        {{--                                [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]--}}
        {{--                                <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
        {{--                            </span>--}}
        {{--                    </div>--}}
        {{--                    <div class="col-md-4">--}}
        {{--                        <label class="center-block image-upload"--}}
        {{--                               for="contact_photo_${updateRowId}">--}}
        {{--                            <figure>--}}
        {{--                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"--}}
        {{--                                     class="img-responsive img-thumbnail"--}}
        {{--                                     id="contact_photo_preview_${updateRowId}" />--}}
        {{--                            </figure>--}}
        {{--                            <input type="hidden" id="contact_photo_base64_${updateRowId}"--}}
        {{--                                   name="contact_photo_base64[]" />--}}
        {{--                        </label>--}}

        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--`);--}}
        {{--            getHelpText();--}}
        {{--        });--}}


        {{--//add shareholder row--}}
        {{--        let rowId = 0;--}}
        {{--        $(".addShareholderRow").on('click', function () {--}}
        {{--            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);--}}
        {{--            let updateRowId = parseInt(lastRowId) + 1;--}}
        {{--            $('#shareholderRow').append(--}}
        {{--                `<tr id="R_${updateRowId}">--}}
        {{--<td>--}}
        {{--<div class="card card-magenta border border-magenta">--}}
        {{--                                                <div class="card-header">--}}
        {{--                                                    Shareholder/partner/proprietor Details Information--}}
        {{--                <span style="float: right; cursor: pointer;" class="m-l-auto">--}}
        {{--                    <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>--}}
        {{--                </span>--}}

        {{--                </div>--}}
        {{--                <div class="card-body" style="padding: 15px 25px;">--}}
        {{--<div class="row">--}}
        {{--<div class="col-md-6">--}}

        {{--<div class="form-group row">--}}
        {{--{!! Form::label('shareholder_name_${updateRowId}', 'Name', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_name[${updateRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_designation[${updateRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_email_${updateRowId}', 'Email', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_email[${updateRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_mobile[${updateRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row">--}}
        {{--                {!! Form::label('shareholder_share_of_${updateRowId}', '% of share', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::number('shareholder_share_of[${updateRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="form-group row" id="nidBlock_${lastRowId + 1}" style="display: none;">--}}
        {{--                {!! Form::label('shareholder_nid_${updateRowId}', 'National ID No', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_nid[${updateRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'National ID No', 'id' => 'shareholder_nid_'.'${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            <div class="form-group row" id="passportBlock_${updateRowId}" style="display: none;">--}}
        {{--                {!! Form::label('shareholder_passport_${updateRowId}', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::text('shareholder_passport[${updateRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--        </div>--}}


        {{-- <div class="col-md-6">--}}
        {{--            <div class="form-group row" style="margin-bottom:0px!important;">--}}
        {{--                {!! Form::label("correspondent_edit_photo_".'${updateRowId}', 'Photograph', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}

        {{--                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
        {{--                        <div class="col-md-8">--}}
        {{--                            <input type="file" style="border:none; margin-bottom: 5px;" class="form-control input-sm correspondent_photo {{ !empty(Auth::user()->user_pic) ? '' : 'required' }}"--}}
        {{--                                   name='correspondent_photo[${updateRowId}]' id='correspondent_edit_photo_${updateRowId}' size="300x300"--}}
        {{--                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${updateRowId}', 'correspondent_photo_base64_${updateRowId}')" />--}}

        {{--                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
        {{--                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]--}}
        {{--                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
        {{--                                        </span>--}}
        {{--                        </div>--}}
        {{--                        <div class="col-md-4">--}}
        {{--                            <label class="center-block image-upload" for='correspondent_photo_preview_${updateRowId}'>--}}
        {{--                                <figure>--}}
        {{--                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
        {{--                                         src="{{asset('assets/images/demo-user.jpg') }}"--}}
        {{--                                         class="img-responsive img-thumbnail" id='correspondent_photo_preview_${updateRowId}'/>--}}
        {{--                                </figure>--}}
        {{--                                <input type="hidden" id='correspondent_photo_base64_${updateRowId}' name="correspondent_photo_base64[${updateRowId}]" />--}}
        {{--                            </label>--}}
        {{--                        </div>--}}



        {{--                </div>--}}
        {{--            </div>--}}
        {{--            </div>--}}
        {{--            <div class="form-group row" style="margin-top:10px;">--}}
        {{--                {!! Form::label('shareholder_dob_${updateRowId}', 'Date of Birth', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">--}}
        {{--                                                            {!! Form::date('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob_${updateRowId}']) !!}--}}
        {{--                --}}{{--                                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
        {{--                <div class="input-group date datetimepicker4" id='dob${updateRowId}' data-target-input="nearest">--}}
        {{--                        {!! Form::text('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${updateRowId}']) !!}--}}
        {{--                <div class="input-group-append" data-target="#dob${updateRowId}" data-toggle="datetimepicker">--}}
        {{--                            <div class="input-group-text">--}}
        {{--                                <i class="fa fa-calendar"></i>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="form-group row">--}}
        {{--{!! Form::label('shareholder_nationality_${updateRowId}', 'Nationality', ['class' => 'col-md-4 required-star']) !!}--}}
        {{--                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">--}}
        {{--                    {!! Form::select('shareholder_nationality[${updateRowId}]', $nationality, '', ['class' => 'form-control nationality shareholder_nationality', 'id' => 'shareholder_nationality_'.'${updateRowId}']) !!}--}}
        {{--                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}--}}
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
        {{--            let today = new Date();--}}
        {{--            let yyyy = today.getFullYear();--}}

        {{--            $('.datetimepicker4').datetimepicker({--}}
        {{--                format: 'DD-MMM-YYYY',--}}
        {{--                maxDate: 'now',--}}
        {{--                minDate: '01/01/' + (yyyy - 110),--}}
        {{--                ignoreReadonly: true,--}}
        {{--            });--}}
        {{--        });--}}

        {{--        $('#shareholderRow').on('click', '.shareholderRow', function () {--}}
        {{--            let prevDataCount = $("#shareholderDataCount").val();--}}

        {{--            let child = $(this).closest('tr').nextAll();--}}

        {{--            child.each(function () {--}}
        {{--                let id = $(this).attr('id');--}}
        {{--                let idx = $(this).children('.row-index').children('p');--}}
        {{--                let dig = parseInt(id.substring(1));--}}
        {{--                idx.html(`Row ${dig - 1}`);--}}
        {{--                $(this).attr('id', `R${dig - 1}`);--}}
        {{--            });--}}
        {{--            $(this).closest('tr').remove();--}}
        {{--            rowId--;--}}
        {{--            $("#shareholderDataCount").val(prevDataCount - 1);--}}
        {{--        });--}}

        // nationality
        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];
            console.log(lastRowId);
            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#passportBlock_' + lastRowId).hide();
                $('#shareholder_nid_' + lastRowId).addClass('required');
                $('#shareholder_passport_' + lastRowId).removeClass('required');
            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#passportBlock_' + lastRowId).show();
                $('#shareholder_passport_' + lastRowId).addClass('required');
                $('#shareholder_nid_' + lastRowId).removeClass('required');
            }
        });

        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }

        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        $('.add_row').click(function () {
            let btn = $(this);
            btn.prop("disabled", true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                async: false,
                type: "POST",
                url: "{{ url('/vsat-license-ammendment-add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled", false);
                }
            });
            getHelpText();

        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });


    });

    // display payment panel
    const fixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
  


    @if($appInfo->status_id != 5)
    // loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
    //     'payment_panel',
    //     "{{ CommonFunction::getUserFullName() }}",
    //     "{{ Auth::user()->user_email }}",
    //     "{{ Auth::user()->user_mobile }}",
    //     "{{ Auth::user()->contact_address }}",
    //     fixed_amounts, JSON.stringify(payOrderInfo));
    @endif
    @if(($appInfo->status_id == 5) &&
        (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0)
    )
    // loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
    //     'payment_panel',
    //     "{{ CommonFunction::getUserFullName() }}",
    //     "{{ Auth::user()->user_email }}",
    //     "{{ Auth::user()->user_mobile }}",
    //     "{{ Auth::user()->contact_address }}",
    //     fixed_amounts, JSON.stringify(payOrderInfo));
    @endif

    function openPreview() {
        if (isCheckedAcceptTerms()) return false;
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function openPreviewV2() {
        if (isCheckedAcceptTerms()) return false;

        let preview = 1;
        // If select online payment
        // if($("#online_payment").is(':checked')) {
        //     new swal({
        //         title: 'Online Payment',
        //         text: 'Online Payment is not available now. Please proceed to Pay Order.',
        //     });
        //     return;
        // }
        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }
        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function isCheckedAcceptTerms() {
        var checkbox = document.querySelector('#accept_terms');
        var errorStatus = false;
        if (!checkbox.checked) {
            checkbox.classList.add('error');
            errorStatus = true;
        } else {
            checkbox.classList.remove('error');
            errorStatus = false;
        }
        return errorStatus;
    }

    function deleteContactRow(element) {
        element.remove();
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

    function ReSubmitForm(){
        if (isCheckedAcceptTerms()) return false;
        document.querySelector("#application_form").submit();
    }

</script>

<script>
    $(function () {

        $("#vts_edit").click(function () {
            if(this.checked)
            {
                $("#vts_license select").removeClass('input_disabled')
            }else{
                $("#vts_license select").addClass('input_disabled')
            }
        });


        $("#satelite_service_provider").click(function () {
            if (this.checked) makeReadWriteByDivId('equipment_tbl');
            else makeReadOnlyByDivId('equipment_tbl');

            if(this.checked)
            {
                $("#equipment_tbl button").removeClass('input_disabled')
            }else{
                $("#equipment_tbl button").addClass('input_disabled')
            }
        });


        $("#hub_operator_info").click(function () {
            if (this.checked) makeReadWriteByDivId('vsat_tbl');
            else makeReadOnlyByDivId('vsat_tbl');

            if(this.checked)
            {
                $("#vsat_tbl button").removeClass('input_disabled')
            }else{
                $("#vsat_tbl button").addClass('input_disabled')
            }
        });



        $("#technical_specification").click(function () {
            if (this.checked) makeReadWriteByDivId('tariffChart_tbl');
            else makeReadOnlyByDivId('tariffChart_tbl');

            if(this.checked)
            {
                $("#tariffChart_tbl button").removeClass('input_disabled')
            }else{
                $("#tariffChart_tbl button").addClass('input_disabled')
            }
        });


        $("#equipment_monitoring").click(function () {
            if (this.checked) makeReadWriteByDivId('listOfEquipment_tbl');
            else makeReadOnlyByDivId('listOfEquipment_tbl');

            if(this.checked)
            {
                $("#listOfEquipment_tbl button").removeClass('input_disabled')
            }else{
                $("#listOfEquipment_tbl button").addClass('input_disabled')
            }
        });


        $("#declarations").click(function() {
            const elements = document.querySelectorAll('#declarationFields input, #declarationFields label, #declarationFields radio, #declarationFields textarea');
            if (this.checked) {
                elements.forEach(item => {
                    item.classList.remove('input_disabled');
                })
            } else {
                elements.forEach(item => {
                    item.classList.add('input_disabled');
                })
            };
        });


    });
</script>

