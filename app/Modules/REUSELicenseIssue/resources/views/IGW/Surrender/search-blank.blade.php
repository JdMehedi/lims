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
        width: 50% !important;
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
    .wizard>.actions {
        width: 70% !important;
        display: none;
    }
</style>
<div class="row">
    <div class="renewForm">
        <div class="card border-magenta">
            <h4 class="card-header">Application for IGW License Surrender</h4>
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
                {{--                <br>--}}
                <h3>Basic Information</h3>
                <fieldset>
                    @include('common.subviews.licenseInfo', ['mode' => 'surrender', 'url' => 'igw-license-cancellation/fetchAppData'])
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'add'])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add'])

                    {{-- Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'add'])

                    {{-- Present Business Actives of the Applicant/Company/Group Company --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Present Business Actives of the Applicant/ Company/ Group Company
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div style="margin-top: 10px;">
                                {{ Form::textarea('present_business_actives', null, array('class' =>'form-control input','id' => 'present_business_actives', 'placeholder'=>'Details', 'cols' => 5, 'rows' =>5, '' => '' ))}}
                            </div>
                        </div>
                    </div>
                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])

                </fieldset>
                <h3>Attachment, Declaration & Submit</h3>
                <fieldset></fieldset>

                <div class="float-left">
                    <a href="{{ url('client/igw-license-surrender/list/'. Encryption::encodeId(4)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>
                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                    </button>
                </div>
                <button type="button" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn" id="save_as_draft" disabled>
                    Save as Draft
                </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>
@include('partials.image-upload')

<script>
    let isAppLimitExceeded = 0;
    $(document).ready(function() {
        getHelpText();
        // setIntlTelInput('.contact_mobile');
        // setIntlTelInput('.shareholder_mobile');
        // setIntlTelInput('.applicant-mobile');

        @isset($companyInfo->office_district)
        getThanaByDistrictId('reg_office_district', ' {{ $companyInfo->office_district ?? '' }}', 'reg_office_thana',{{ $companyInfo->office_thana ?? '' }})
        @endisset

        let form = $("#application_form").show();
        let popupWindow = null;
        // step part
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

                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total !== 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }

                    //validate client side rendered fields
                    // const requiredClientFields = document.querySelectorAll('.client-rendered-row input, .client-rendered-row select');
                    // let nextAvailable = true;
                    // for(const elem of requiredClientFields) {
                    //     if(elem.classList.contains('required') && !elem.value) {
                    //         elem.classList.add('error');
                    //         nextAvailable = false;
                    //     }
                    // }
                    // if(!nextAvailable) return false;

                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex === 2) {
                    // declarationSectionValidation();

                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                // return true;
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
                    if(currentIndex > newIndex) {
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
                if (newIndex < currentIndex) {
                    return true;
                }
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if(!form.valid()) errorStatus = true;
                if(errorStatus) return false;
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
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>', 'Sample', '');--}}
            openPreviewV2();
        });

        // datepicker part
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        // declaration part
        $("#declaration_q1_yes").on('click', function() {
            $('#if_declaration_q1_yes').css('display','inline');
        });
        $("#declaration_q1_no").on('click', function() {
            $('#if_declaration_q1_yes').css('display','none');
        });
        $("#declaration_q2_yes").on('click', function() {
            $('#if_declaration_q2_yes').css('display','inline');
        });
        $("#declaration_q2_no").on('click', function() {
            $('#if_declaration_q2_yes').css('display','none');
        });
        $("#declaration_q3_yes").on('click', function() {
            $('#if_declaration_q3_yes').css('display','inline');
        });
        $("#declaration_q3_no").on('click', function() {
            $('#if_declaration_q3_yes').css('display','none');
        });

        //get share value and number of share
        $("#shareholderRow").on("keyup", ".share-value, .no-of-share", function(){
            calculateShareValue();
        });

        // total no. of share and total value calculation
        function calculateShareValue(){
            let totalNoOfShare = 0;
            $(".share-value").each(function(){
                totalNoOfShare +=  parseInt(this.value) || 0;
            });
            $("#total_share_value").val(totalNoOfShare);

            let totalShareValue = 0;
            $(".no-of-share").each(function(){
                totalShareValue += parseInt(this.value) || 0;
            });
            $("#total_no_of_share").val(totalShareValue);
        }

        // license type part
        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                isAppLimitExceeded = 0;
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                addEventListenerToIspType('isp_licensese_area_division')
            }

            if(this.value == 3){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control isp_licensese_area_district','data-type' => '3', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
                addEventListenerToIspType('isp_licensese_area_district')
            }

            if(this.value == 4){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control isp_licensese_area_district', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');

                addEventListenerToIspType('isp_licensese_area_thana')

            }
            getHelpText();
        });
        $(document).on('change','#isp_licensese_area_division' ,function (){
            $("#isp_licensese_area_thana").prop('selectedIndex',0);
        });

        // copnay type part
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

        // add row part
        $('.add_row').click(function(){
            var btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,

                },
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled",false);
                    getHelpText();
                }
            });
        });
        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // payment panel part
        $("#type_of_isp_licensese").change(function() {
            let oss_fee = 0;
            let vat = 0;
            $.ajax({
                type: "POST",
                url: "{{ url('igw-license-issue/get-payment-data-by-license-type') }}",
                data: {
                    process_type_id: {{ $process_type_id }},
                    payment_type: 1,
                    license_type: $(this).val()
                },
                success: function(response) {
                    if(response.responseCode == -1){
                        alert(response.msg);
                        return false;
                    }
                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function() {
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0,
                        7: 0,
                        8: 0,
                        9: 0,
                        10: 0
                    };
                    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        });
        $(function() {
            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });
    });

    function generateBase64String(source, destination){
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

    // preview
    function openPreview() {
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }
    function openPreviewV2() {
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

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }

        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function deleteContactRow(element) {
        element.remove();
    }
    function declarationSectionValidation(){
        let error_status = false ;
        if($("#declaration_q1_yes").is(":checked") && $("#if_declaration_q1_yes").val() === ""){
            $("#if_declaration_q1_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === ""){
            $("#if_declaration_q2_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q3_yes").is(":checked") && $("#if_declaration_q3_yes").val() === ""){
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }

        return error_status;
    }
    function SetErrorInShareOfInputField(){
        $( ".shareholder_share_of" ).each(function( index ) {
            $(this).addClass('error');
        });
    }

    // payemnt

    function removePayOrder(rowId){
        $("#single_pay_order_"+rowId).remove();
    }

    const ispLicenseAreaListeners = {
        'isp_licensese_area_division' : 0,
        'isp_licensese_area_district' : 0,
        'isp_licensese_area_thana' : 0,
    };
    function addEventListenerToIspType(selector) {
        const element = document.getElementById(selector);
        if(!ispLicenseAreaListeners[selector]){
            element.addEventListener("change", function(event) {
                const ispLicenseType = document.getElementById('type_of_isp_licensese').value;
                const currentIspLicenseType = event.target.dataset.type;
                const ISP_LICENSE_TYPES = {
                    2: 'isp_licensese_area_division',
                    3: 'isp_licensese_area_district',
                    4: 'isp_licensese_area_thana',
                };
                let areaId =  ISP_LICENSE_TYPES[ispLicenseType] ? document.getElementById(ISP_LICENSE_TYPES[ispLicenseType]).value : '';

                if(!areaId || (ispLicenseType != currentIspLicenseType)){
                    return
                }
                $.ajax({
                    type: "POST",
                    url: "{{ url('isp-license-issue/check-application-limit') }}",
                    data: {ispLicenseType,areaId},
                    success: function(response) {
                        // error
                        if(parseInt(response.responseCode) == 2){
                            alert(response.message);
                            return false;
                        }

                        isAppLimitExceeded = parseInt(response.responseCode);
                        if(isAppLimitExceeded) alert("According to the BTRC guideline you are not allowed to apply for this category license in this designated area.");

                    },
                    error: function(error){
                        alert(error)
                    }
                });
            });
            ispLicenseAreaListeners[selector] = 1;
        }

    }

</script>

