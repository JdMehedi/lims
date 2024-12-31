<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />

<style>
    .font-normal {
        font-weight: normal;
    }
    .input-disabled {
        pointer-events: none;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .w-60 {
        width: 70px !important;
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
    .verticalAlignMiddle{
        vertical-align: middle !important;
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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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
        width: 33.2% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>
<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application For New IGW License Issue</h4>
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
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Contact Person --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'add', 'extra' => ['address2']])

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'add'])
                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Necessary attachment --}}
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'add'])

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
                                                Has any Application for License of IGW been rejected before?
                                            </label>
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Has any License of IGW issued previously to the Applicant/ any Share Holder/ Partner been cancelled?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Do the Applicant/ any Share Holder/ Partner hold any other Operator Licenses from the Commission?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{--                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes', 'accept'=>'image/*', 'onchange'=>"generateBase64String('if_declaration_q3_yes','declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}--}}
                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes','onchange'=>"createObjUrl(event, 'declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}
                                                <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >
                                            </div>
                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/ terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Note --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Note <span style="float: right;"></span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol style="list-style-type:disc;">
                                        <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/ fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                                        <li>Application without the submission of complete documents and information will not be accepted.</li>
                                        <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                                        <li>Fees and charges are not refundable.</li>
                                        <li>The Commission is entitled to change this from time to time if necessary.</li>
                                        <li>Updated documents shall be submitted during application.</li>
                                        <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                        {{--                                        <li>For New Applicant only A, B and E will be applicable.</li>--}}
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
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/igw-license-issue/list/'. Encryption::encodeId(78)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>
                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                    </button>
                </div>
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn" id="save_as_draft">
                    Save as Draft
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
                        Terms and Conditions <span style="float: right;"><button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication
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
    $(document).ready(function() {
        getHelpText();

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

                    $(".shareholder_nationality").each(function (){
                        let id = $(this).attr('id');
                        let countryCode = $(this).val();
                        console.log(countryCode);
                        let lastId = id.split('_')[2];
                        if(countryCode == 18){
                            let nidValue = $("#shareholder_nid_"+lastId).val();
                            if(nidValue.length == 10 || nidValue.length == 13 || nidValue.length == 17){
                                errorStatus = false;
                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please provide valid NID number.',
                                });
                                $(this).addClass('error')
                                errorStatus = true;
                            }
                        }

                    });


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
            openPreviewV2();
        });

        // // datepicker part
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });

        datePickerHide('datetimepicker4');

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

        // copnay type part
        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });
        let company_type = "{{$companyInfo->org_type}}";
        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
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
        var unfixed_amounts = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
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

</script>

