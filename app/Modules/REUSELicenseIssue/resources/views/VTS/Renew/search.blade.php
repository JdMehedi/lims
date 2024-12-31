<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
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

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

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

    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 400;
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
        width: 33.2% !important;
    }

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
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

    #renewForm input[type=checkbox] {
        vertical-align: bottom;
        width: 15px;
        height: 15px;
    }

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-md-12" id="renewForm">
        <div class="card border-magenta">
            <h4 class="card-header">Application for VTS License Renewal</h4>
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

                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id','', ['class' => 'form-control input-md', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}

                    {!! Form::hidden('issue_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}


                </div>
                <h3>Basic Information</h3>
                <fieldset>
                    {{--                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])--}}
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.licenseInfo', [ 'mode' => 'renew-form', 'url' => 'vts-license-renew/fetchAppData'])

                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1])
                    {{-- Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1])
                    {{-- Share Holder --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'renew'])

                </fieldset>

                <h3>Attachment, Declaration & Submit</h3>
                <br>
                <fieldset>
                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Required Documents for attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

                    {{--Declaration--}}
                    <div class="card card-magenta border border-magenta mt-4">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li><span class="i_we_dynamic">I/We</span> declare that all the information furnished in
                                            this application form is true and correct. <span class="i_we_dynamic">I/We</span> have
                                            carefully read the guidelines/terms and conditions, for the license and <span
                                                class="i_we_dynamic">I/We</span> understand that approval from the Commission for
                                            this application is based on information as declared in this application Should any of
                                            the information as declared be incorrect, then any License granted by the Commission may
                                            be
                                        </li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> also declare that I/we
                                            have read, understood and undertake to comply with ad the terms and conditions outlined
                                            or referred to in the Commision document ented Regulatory and Licensing Guidelines for
                                            invitation of application for granting of Gicense in the country, and those terms and
                                            conditions included in the License to be issued to us/me, if this application is
                                            approved by the Commission
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Payment & Submit</h3>
                {{--                <br>--}}
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

                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:20px;']) }}
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
                    <a href="{{ url('client/vts-license-renew/list/'. Encryption::encodeId(30)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" onclick="openPreviewV2()" name="actionBtn">Submit
                        </button>
                    @elseif($appInfo->status_id == 5)
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md"
                                value="Re-submit" onclick="openPreviewV2()" name="actionBtn">Re-submit
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


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>

    var selectCountry = '';
    // setIntlTelInput('.shareholder_mobile');
    // setIntlTelInput('.contact_person_mobile');
    $(document).ready(function () {
        // applicant profile
        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
        @endisset

        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset

        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
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
        // loadApplicationDocs('docListDiv', 'stable_small_forgen');

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
                        SetErrorInShareOfInputField();
                        return false;
                    }


                    // Allways allow previous action even if the current form is not valid!
                    console.log(currentIndex, newIndex)
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

                if (newIndex < currentIndex) return true;

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
                    form.find('#submitForm').css('display', 'none');

                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");
                } else {
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
                console.log('finished');
                return false;
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

        $("#company_type").css('pointer-events', 'none');

        var popupWindow = null;

        $('.finish').on('click', function (e) {
            if (!$('#accept_terms').prop('checked')) {
                $('#accept_terms').addClass('error');
                return false;
            }
            popupWindow = window.open('<?php echo URL::to('process/license/preview/' . \App\Libraries\Encryption::encodeId($process_type_id)); ?>');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);
            console.log(key);
            loadApplicationDocs('docListDiv', key);
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
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>


<script>

    $(document).ready(function () {

        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
            $('#declaration_q1_application_date').addClass('required date');
            $('#declaration_q1_text').addClass('required');
        });

        $("#declaration_q1_no").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'none');
        });

        $("#declaration_q2_yes").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'inline');
            $('#declaration_q2_text').addClass('required');
        });

        $("#declaration_q2_no").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'none');
        });

        $("#declaration_q3_yes").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'inline');
            $('#declaration_q3_application_date').addClass('required date');
            $('#declaration_q3_text').addClass('required');
        });

        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
        });

        $("#declaration_q4_yes").on('click', function () {
            $('#if_declaration_q4_yes').css('display', 'inline');
            getHelpText();
        });
        $("#declaration_q4_no").on('click', function () {
            $('#if_declaration_q4_yes').css('display', 'none');
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

    });
    // load payment panel
    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        fixed_amounts);

    function openPreview() {

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            return false;
        } else {
            $('#accept_terms').removeClass('error');
        }

        window.open('<?php echo URL::to('process/license/preview/' . \App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }


    function openPreviewV2() {
        let preview = 1;
        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if (!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            return false;
        }

        if (preview) window.open('<?php echo URL::to('process/license/preview/' . \App\Libraries\Encryption::encodeId($process_type_id)); ?>');
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
            document.getElementById(img_preview_id).setAttribute('src', '<?php echo e(url('assets/images/no_image.png')); ?>');
        }
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

</script>

