<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
    }
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard>.content {
        overflow: visible;
    }

    .wizard>.actions {
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

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard>.actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard>.content>.body label {
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

    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .border-header-box{
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }
    .border-header-txt{
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    #renewForm input[type=checkbox]{
        vertical-align: bottom;
        width: 15px;
        height: 15px;
    }
    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>
<div id="renewForm">

    {!! Form::open([
        'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
    ]) !!}
    @csrf
    <div style="display: none;" id="pcsubmitadd"></div>
{{--    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}--}}
    <h3>Basic Information</h3>
{{--    <br>--}}
    <fieldset>
        {!! Form::hidden('form_version', 'v2', ['class' => 'form-control input-md required', 'id' => 'form_version']) !!}
        @includeIf('common.subviews.licenseInfo', [ 'mode' => 'renew-serarch', 'url' => 'iig-license-renew/fetchAppData'])

        {{-- Company info --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'add'])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add'])

        {{--contact person--}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'add'])

        {{-- Shareholder/partner/proprietor Details --}}
        @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

    </fieldset>

    {{--Attachment Declration--}}
    <h3>Attachment & Declration</h3>
    {{--                <br>--}}
    <fieldset>
        {{--Required Documents--}}
        @includeIf('common.subviews.RequiredDocuments', ['mode' => 'add'])
        {{--Declaration--}}
        <div class="card card-magenta border border-magenta mt-4">
            <div class="card-header">
                Declaration
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-12">
                        <ol>
                            <li ><span class="i_we_dynamic">I/We</span> declare that all the information furnished in this application form is true and correct. <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> understand that approval from the Commission for this application is based on information as declared in this application Should any of the information as declared be incorrect, then any License granted by the Commission may be</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> also declare that I/we have read, understood and undertake to comply with ad the terms and conditions outlined or referred to in the Commision document ented Regulatory and Licensing Guidelines for invitation of application for granting of Gicense in the country, and those terms and conditions included in the License to be issued to us/me, if this application is approved by the Commission</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>

    {{--Payment and Submit--}}
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
                        {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="float-left">
        <a href="{{ url('/iig-license-renew/list/'. Encryption::encodeId($process_type_id)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
           id="save_as_draft">Close
        </a>
    </div>

    <div class="float-right">
{{--        @if(!in_array($appInfo->status_id,[5]))--}}
            <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                    class="btn btn-success btn-md" value="submit" onclick="openPreviewV2()" name="actionBtn">Submit
            </button>
{{--        @endif--}}

{{--        @if($appInfo->status_id == 5)--}}
{{--            <button type="submit" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"--}}
{{--                    class="btn btn-info btn-md"--}}
{{--                    value="Re-submit" name="actionBtn">Re-submit--}}
{{--            </button>--}}
{{--        @endif--}}
    </div>

{{--    @if(!in_array($appInfo->status_id,[5]))--}}
        <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                name="actionBtn" id="save_as_draft">Save as Draft
        </button>
{{--    @endif--}}
    {!! Form::close() !!}
</div>

<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>
    //let selectCountry = '';

    $(document).ready(function() {

        //this is for mobile plugin input field width change.
        $("form").find('.iti').css({
            "width": "-moz-available",
            "width": "-webkit-fill-available"
        });
        $(document).on('keydown', '#local_wc_ivst_ccy', function(e) {
            if (e.which == 9) {
                e.preventDefault();
                $('#usd_exchange_rate').focus();
            }
        })
        $(document).on('keydown', '#usd_exchange_rate', function(e) {
            if (e.which == 9) {
                e.preventDefault();
                $('#ceo_taka_invest').focus();
            }
        })
        $(document).on('change', '.companyInfoChange', function(e) {
            $('#same_address').trigger('change');
        })
        $(document).on('blur', '.companyInfoInput', function(e) {
            $('#same_address').trigger('change');
        })
        $(document).on('change', '#permanentSameAsRegisterdAddress', function(e) {
            if(this.checked === true){
                let office_district = $("#office_district").val();
                let office_upazilla_thana = $("#office_upazilla_thana").val();
                $("#par_office_district").val(office_district);

                getThanaByDistrictId('par_office_district', office_district, 'par_office_upazilla_thana',office_upazilla_thana.trim());

                $("#par_office_address").val($("#office_address").val());
            }
            if(this.checked === false) {
                $("#par_office_upazilla_thana").val('');
                $("#par_office_address").val('');
                $("#par_office_district").val('');
            }
        })

        $(document).on('change', '#signatorySameAsContact', function(e) {
            if(this.checked === true){
                let contact_person_name = $("#contact_person_name_1").val();
                let contact_designation = $("#contact_designation_1").val();
                let contact_mobile = $("#contact_mobile_1").val();
                let contact_email = $("#contact_person_email_1").val();
                let contact_district = $("#contact_district_1").val();
                let contact_thana = $("#contact_thana_1").val() || '0';
                let contact_person_address = $("#contact_person_address_1").val();
                let contact_photo_base64 = $("#correspondent_contact_photo_base64_1").val()

                getThanaByDistrictId('contact_signatory_district_1', contact_district, 'contact_signatory_thana_1',contact_thana.trim());

                $("#contact_signatory_name").val(contact_person_name);
                $("#contact_signatory_designation").val(contact_designation);
                $("#contact_signatory_mobile").val(contact_mobile);
                $("#contact_signatory_email").val(contact_email);
                $("#contact_signatory_person_address").val(contact_person_address);
                $("#contact_signatory_district_1").val(contact_district);
                $("#contact_signatory_thana_1").val(contact_thana);
                $("#contact_signatory_photo_preview").attr('src',contact_photo_base64);
                $("#contact_signatory_photo_base64").val(contact_photo_base64);
                $("#contact_signatory_photo").addClass("signatoryImg");
                $("#contact_signatory_photo").removeClass("required");
                $("#contact_signatory_photo").removeClass("error");


            }
            if(this.checked === false) {
                $("#contact_signatory_name").val('');
                $("#contact_signatory_designation").val('');
                $("#contact_signatory_mobile").val('');
                $("#contact_signatory_email").val('');
                $("#contact_signatory_person_address").val('');
                $("#contact_signatory_district_1").val('');
                $("#contact_signatory_thana_1").val('');
                $("#contact_signatory_photo_preview").attr('src','');
                $("#contact_signatory_photo_base64").val('');
                $("#contact_signatory_photo").addClass("required");
                $("#contact_signatory_photo").addClass("error");
                $("#contact_signatory_photo").removeClass("signatoryImg");
            }
        })



        let check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function() {

            if (this.checked === false) {
                $('#company_factory_div').removeClass('hidden');
                this.checked = false;
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

        // Sales (in 100%)
        $("#local_sales_per").on('keyup', function() {
            let local_sales_per = this.value;
            if (local_sales_per <= 100 && local_sales_per >= 0) {
                let cal = 100 - local_sales_per;
                $('#foreign_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        $("#foreign_sales_per").on('keyup', function() {
            let foreign_sales_per = this.value;
            if (foreign_sales_per <= 100 && foreign_sales_per >= 0) {
                let cal = 100 - foreign_sales_per;
                $('#local_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });


        LoadListOfDirectors();

        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }

                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex == 2) {
                    let returnFlag = false;
                    const multiple_selectelements = document.getElementsByClassName("multiple_select2");
                    for(const element of multiple_selectelements){
                        const parentElmentChild = element.parentElement.children;
                        if(parentElmentChild[0].classList.contains('required')){
                            const spanElement = parentElmentChild[1].children[0].children[0];
                            if(!parentElmentChild[0].value){
                                spanElement.classList.add("selecttionError");
                                returnFlag = true;
                            }else{
                                spanElement.classList.remove("selecttionError");
                            }
                        }
                    }
                    if(returnFlag) return false;
                }

                if (currentIndex > newIndex) {
                    return true;
                }

                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3) {
                    // let dec_validation_status = declarationSectionValidation();
                    // if(!dec_validation_status){
                    //     return dec_validation_status;
                    // }
                    let errorStatus = false;
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                if ($("#declaration_q4_yes").is(":checked") || $("#declaration_q4_no").is(":checked")) {
                                    errorStatus =  false;
                                }else{
                                    new swal({
                                        type: 'error',
                                        text: 'Please answer the Declaration section all question.',
                                    });
                                    errorStatus = true;
                                }
                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    }else{
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }
                    if($("#declaration_q4_yes").is(":checked")){
                        let checkeddval = $("input[name='declaration_q4_given_commision']:checked").val();
                        if(checkeddval == undefined || checkeddval == "" || checkeddval == null){
                            $("#declaration_q4_given_commision_yes").addClass('error');
                            $("#given_commision_text").addClass('text-danger');
                            errorStatus = true;

                        }else{
                            $("#given_commision_text").removeClass('text-danger');
                            $("#declaration_q4_given_commision_yes").removeClass('error');
                            errorStatus = false;
                        }
                    }

                    if(errorStatus) return false;
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
                    form.find('#submitForm').css('display', 'none');
                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'none');
                    // form.steps("next");
                }
                if (currentIndex === 3) {
                    form.find('#submitForm').css('display', 'inline');
                    // form.steps("next");
                }
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

        let popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")){
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

            if(!$('#accept_terms').prop('checked')){
                $('#accept_terms').addClass('error');
                return false;
            }
            popupWindow = window.open('<?php echo URL::to('/iig-license-issue/preview'); ?>', 'Sample', '');
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
        function SetErrorInShareOfInputField(){
            $( ".shareholder_share_of" ).each(function( index ) {
                $(this).addClass('error');
            });
        }

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
        // $(".mobile_number").intlTelInput({
        //     // hiddenInput: "applicant_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#applicant_mobile").intlTelInput({
        //     hiddenInput: "applicant_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $(".contact_mobile").intlTelInput({
        //     // hiddenInput: "contact_mobile_1",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#contact_signatory_mobile").intlTelInput({
        //     hiddenInput: "contact_signatory_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
        // $("#shareholder_mobile").intlTelInput({
        //     hiddenInput: "shareholder_mobile",
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true
        // });
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

        $(function() {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
            $('.datetimepickerCommencement').datetimepicker({
                format: 'DD-MMM-YYYY',
                // maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

//add shareholder row
        let rowId = 0;

        $('#shareholderRow').on('click', '.shareholderRow', function () {
            let prevDataCount = $("#shareholderDataCount").val();

            let child = $(this).closest('tr').nextAll();

            child.each(function () {
                let id = $(this).attr('id');
                let idx = $(this).children('.row-index').children('p');
                let dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#shareholderDataCount").val(prevDataCount - 1);
        });

        // nationality
        $(document).on('change','.shareholder_nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#passportBlock_'+lastRowId).hide();

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).show();
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
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        let company_type = "{{$companyInfo->org_type}}";
        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 3){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        $('.add_row').click(function(){
            let btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                type: "POST",
                url: "{{ url('iptsp-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    getHelpText();
                }
            });
        });

        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        $(".multiple_select2").select2({
            //maximumSelectionLength: 1
            closeOnSelect : false,
            placeholder : "Select",
            allowHtml: true,
            allowClear: true,
            tags: true // создает новые опции на лету
        });

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
                success: function(response) {
                    let html = '';
                    if (response.responseCode == 1) {

                        let edit_url = "{{ url('/client/company-profile/edit-director') }}";
                        let delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                        let count = 1;
                        $.each(response.data, function(id, value) {
                            let sl = count++;
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
                            let date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
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

    });

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
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }

        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }
</script>

