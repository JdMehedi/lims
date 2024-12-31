<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style type="text/css">
    /* Latest compiled and minified CSS included as External Resource*/

    /* Optional theme */

    /*@import url('//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css');*/

    .addressField {
        width: 79.5%;
        float: right;
    }
    .stepwizard-step p {
        margin-top: 0px;
        color:#666;
    }
    .stepwizard-row {
        display: table-row;
    }
    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }
    .stepwizard-step button[disabled] {
        /*opacity: 1 !important;
        filter: alpha(opacity=100) !important;*/
    }
    .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
        opacity:1 !important;
        color:#bbb;
    }
    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content:" ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-index: 0;
    }
    .stepwizard-step {
        font-weight: bold;
        display: table-cell;
        text-align: center;
        position: relative;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }
    label {
        font-weight: normal;
        font-size: 16px;
    }
    span{
        font-size: 16px;
    }
</style>
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <strong>Company Association</strong>
        </div>
        <div class="panel-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-input">company_name_english
                                {!! Form::label('company_name_bangla', trans('CompanyProfile::messages.company_name_bangla'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->org_nm_bn }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 form-input">
                                {!! Form::label('company_name_english', trans('CompanyProfile::messages.company_name_english'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_name_english') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->org_nm }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-input">
                                {!! Form::label('reg_type_id', trans('CompanyProfile::messages.reg_type'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('reg_type_id') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->reg_type }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 form-input">
                                {!! Form::label('company_type_id', trans('CompanyProfile::messages.company_type'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_type') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->company_type }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-input">
                                {!! Form::label('company_office_division_id', trans('CompanyProfile::messages.division'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_office_division_id') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->division }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 form-input">
                                {!! Form::label('company_office_district_id', trans('CompanyProfile::messages.district'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_office_district_id') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->district }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-input">

                            </div>

                            <div class="col-md-6 form-input">
                                {!! Form::label('company_office_thana_id', trans('CompanyProfile::messages.thana'),
                                ['class'=>'col-md-5']) !!}
                                <div class="col-md-7 {{$errors->has('company_office_thana_id') ? 'has-error': ''}}">
                                    : <span>{{ $appInfo->thana }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                {{--		        </div>--}}

        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {

        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn'),
            allPreviousBtn = $('.previousBtn')
        ;

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-success').addClass('btn-default');
                $item.addClass('btn-success');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allPreviousBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                previousStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

            previousStepWizard.trigger('click');
        });

        allNextBtn.click(async function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='checkbox'],input[type='radio'],input[type='url'],select"),
                isValid = true;



            $(".form-input").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-input").addClass("has-error");
                }
            }

            if(isValid && curStepBtn == 'step-1'){
                $.when(
                    getCompanyData(),
                    console.log(2345)
                ).then(function() {
                    console.log('22')
                    if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
                });

            }else{
                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            }


        });

        $('div.setup-panel div a.btn-success').trigger('click');
    });


    function getCompanyData() {

        $.ajax({
            url: "{{ url("client/company-profile/cpmpany-association/company-info")}}",
            type: "POST",
            data: {
                companyname: $('#company_name_english').val(),
                companydistrict: $('#company_office_district_id').val(),
                _token : $('input[name="_token"]').val()
            },
            success: function(response){
                if (response.responseCode == 1){
                    var message = '';
                    var cl = ""
                    $("#status_message").removeClass('alert alert-danger alert-success');
                    if(response.status == 1){
                        cl = "alert alert-success"
                        message = "আপনার কোম্পানিটি পাওয়া গিয়েছে। এপ্রুভাল এর জন্য নিচের ব্যবহারকারীকে এসাইন করুন";
                        $("#user_type_section").show();
                        $("#bscic_user_section").hide()
                        $("#company_user_section").hide();
                        $("#save_company").hide();
                        $("#save_company_yes").prop('required',false);
                        $(".user_type").prop('required',true);
                        html = '<option value="">নির্বাচন করুন</option>';
                        console.log(response.companyusers);
                        $.each(response.companyusers, function(index, value) {
                            html += '<option value="' + index+ '" >' + value + '</option>';
                        });
                        $('#company_user').html(html);
                    }else{
                        cl = "alert alert-danger"
                        message = "Company Not Found";
                        $("#user_type_section").hide();
                        $("#bscic_user_section").hide()
                        $("#company_user_section").hide();
                        $("#save_company").show();
                        $("#save_company_yes").prop('required',true);
                        $(".user_type").prop('required',false);
                    }

                    $("#status_message").text(message);
                    $("#status_message").addClass(cl);
                }
            }
        });

    }

    $(".user_type").on('change',function(){
        var userType = $(this).val();
        if(userType == "bscic"){
            $("#bscic_user_section").show();
            $("#company_user_section").hide();
            $("#bscic_user").prop('required',true);
            $("#company_user").prop('required',false);
        }else if(userType == "company"){
            $("#company_user_section").show();
            $("#bscic_user_section").hide()
            $("#bscic_user").prop('required',false);
            $("#company_user").prop('required',true);
        }else{
            $("#bscic_user_section").hide()
            $("#company_user_section").hide();
            $("#bscic_user").prop('required',false);
            $("#company_user").prop('required',false);
        }
    })
</script>
