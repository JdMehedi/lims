<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
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

    .mr-5 {
        margin-right: 5px;
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

    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
    }

    .verticalAlignMiddle {
        vertical-align: middle !important;
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

    .wizard > .content > .body ul > li {
        display: list-item !important;
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
        <div class="card border-magenta" style="border-radius: 10px;">
            <div class="card-header d:flex justify-between">
                <h4>Application for IIG License Issue</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye" style="margin-right: 5px;"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
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
                    'files' => 'true',
                ]) !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">
                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}

                </div>
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'edit', 'selected' => 1 ])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'edit', 'selected' => 1 ])

                    {{-- Contact Person Profile --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'edit', 'selected' => 1 ])

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'edit','selected' => 1])
                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
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
                                        <li ><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> declare that all the information furnished in this application form is true and correct. <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> understand that approval from the Commission for this application is based on information as declared in this application Should any of the information as declared be incorrect, then any License granted by the Commission may be</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span> also declare that I/we have read, understood and undertake to comply with ad the terms and conditions outlined or referred to in the Commision document ented Regulatory and Licensing Guidelines for invitation of application for granting of Gicense in the country, and those terms and conditions included in the License to be issued to us/me, if this application is approved by the Commission</li>
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
                    @if($appInfo->status_id != 5)
                        <div id="payment_panel"></div>
                    @elseif(($appInfo->status_id == 5) && (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0))
                        <div id="payment_panel"></div>
                    @endif

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
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="float-left">
                    <a href="{{ url('/iig-license-issue/list/'. Encryption::encodeId($process_type_id)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md" value="submit" name="actionBtn"
                                onclick="openPreviewV2()">Submit
                        </button>
                    @endif
                    @if($appInfo->status_id == 5)
                        <button type="submit" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md"
                                value="Re-submit" name="actionBtn">Re-submit
                        </button>
                    @endif
                </div>

                @if(!in_array($appInfo->status_id,[5]))
                    <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                            name="actionBtn" id="save_as_draft">Save as Draft
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
                                <ol style="list-style-type:disc;">
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
                                </ol>
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
    let isAppLimitExceeded = 0;
    $(document).ready(function () {
        getHelpText();

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

        // step part
        let form = $("#application_form").show();
        let popupWindow = null;
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                let errorStatus = false;
                if (newIndex == 1) {

                    if(isAppLimitExceeded){
                        alert("According to the BTRC guideline you are not allowed to apply for this category license in this designated area.")
                        return false;
                    }
                    var total = 0;
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

                    // //validate client side rendered fields
                    // const requiredClientFields = document.querySelectorAll('.client-rendered-row input, .client-rendered-row select');
                    // let nextAvailable = true;
                    // for(const elem of requiredClientFields) {
                    //     if(elem.classList.contains('required') && !elem.value) {
                    //         elem.classList.add('error');
                    //         nextAvailable = false;
                    //     }
                    // }
                    // if(!nextAvailable) return false;
                }
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
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

                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if (!form.valid()) errorStatus = true;
                if (errorStatus) return false;
                return true;
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
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>', 'Sample', '');--}}
            openPreviewV2();
        });

        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        // datepicker part
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

        //get share value and number of share
        $("#shareholderRow").on("keyup", ".share-value", function () {
            calculateShareValue();
        });

        $("#shareholderRow").on('keyup', '.no-of-share', function () {
            calculateShareValue();
        });

        // total no. of share and total value calculation
        function calculateShareValue() {
            let sum_share = 0;
            $(".share-value").each(function () {
                sum_share = sum_share + parseInt(this.value);
            });
            $("#total_share_value").val(sum_share);
            // let sum_value = document.getElementById('total_no_of_share').value === '' ? 0 : parseInt(document.getElementById('total_no_of_share').value);
            let sum_value = 0;
            $(".no-of-share").each(function () {
                sum_value = sum_value + parseInt(this.value);
            });
            $("#total_no_of_share").val(sum_value);
        }

        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });
        var company_type = "{{$appInfo->org_type}}";
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

        // payment part
        @if($appInfo->status_id != 5)
        // load payment panel
        const fixed_amounts = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0
        };
        loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
            'payment_panel',
            "{{ CommonFunction::getUserFullName() }}",
            "{{ Auth::user()->user_email }}",
            "{{ Auth::user()->user_mobile }}",
            "{{ Auth::user()->contact_address }}",
            fixed_amounts);
        @endif
        // add row part
        $('.add_row').click(function () {
            var btn = $(this);
            btn.prop("disabled", true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
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
                    btn.prop("disabled", false);
                    getHelpText();
                }
            });
        });
        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });
    });

    function mobile_no_validation(id) {
        var onlyNumber = $("#" + id).val();
        var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode ?? "880";
        var regex = /^01[0-9]{9}$/;
        var result = regex.test(onlyNumber);

        if (countryCode === '880') {
            if (onlyNumber === "") {
                console.log('label:', 0, `#${id}`)
                $(`#${id}`).addClass('error');
            } else if (result === false) {
                console.log('label:', 1)
                $(`#${id}`).addClass('error');
            } else {
                console.log('label:', 2)
                $(`#${id}`).removeClass('error');
            }
        }
    }

    function generateBase64String(source, destination) {
        const inputfile = document.getElementById(source);
        const file = inputfile.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            document.getElementById(destination).value = reader.result;
        };
        reader.onerror = function (error) {
            console.log(error);
        };
    }

    function openPreview() {

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').focus()
            $('#accept_terms').addClass('error');
            return false;
        }

        window.open('<?php echo URL::to( '/iig-license-issue/preview' ); ?>');
    }

    function openPreviewV2() {
        let preview = 1;
        // If select online payment
        const onlinePayment = "{{config('payment.online_payment')}}";
        if ($("#online_payment").is(':checked') && !onlinePayment) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }
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

        if (preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
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

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

</script>
