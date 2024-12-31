<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Preview Form</title>
    <link rel="stylesheet"
          href="{{ asset("assets/plugins/bootstrap/css/bootstrap4.min.css") }}"/>
    {{--    <link rel="stylesheet" href="{{ asset("assets/stylesheets/css/main.min.css") }}">--}}
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom.css") }}"/>
    <script src="{{ asset("assets/plugins/jquery/jquery.min.js") }}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.min.css") }}"/>
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script language="javascript"> var jQuery = jQuery.noConflict(true);</script>

    <style type="text/css">
        /*.flag-container, .selected-dial-code{*/
        /*    float: left;*/
        /*    line-height: 30px;*/
        /*    padding-left: 10px;*/
        /*}*/

        .cr-boundary, .cr-slider-wrap {
            display: none;
        }

        .iti__flag-container {
            display: none;
        }
    </style>
</head>
<body>
<div align="right">
    <input type="button" value="&nbsp;&nbsp;&nbsp; Close &nbsp; &nbsp;&nbsp;" align="right" onClick="CloseMe()"
           id="closeBtn" class="btn-submit-1 btn btn-danger" style="position: fixed;right: 0;z-index:999;"/>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div id="previewDiv"></div>
        </div>
    </div>
</div>
<div align="center">
    <input type="button" style="font-size: 18px;" value="Go Back" id="backBtn" onclick="CloseMe()"
           class="btn-submit-1 btn btn-danger"/>
    <input type="button" style="font-size: 18px;" value="Submit" id="submitFromPreviewBtn" onclick=""
           class="btn-submit-1 btn btn-primary"/>
</div>
</body>
</html>
<script language="javascript">

    var status_id = window.opener.document.getElementById('status_id');

    if (status_id && status_id.value == 5) {
        document.getElementById('submitFromPreviewBtn').value = 'Re-Submit';
    }

    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return val;
    }

    jQuery(function () {
        jQuery('#submitFromPreviewBtn').click(function (e) {
            window.opener.jQuery("input[type='file']").prop("disabled", false);
            window.opener.document.getElementById("application_form").setAttribute("target", "_self");
            const element = '<input type="hidden" name="actionBtn" value="submit"/>';
            window.opener.document.getElementById("application_form").insertAdjacentHTML('beforeend', element);
            window.opener.jQuery("#application_form")[0].submit();
            window.close();
        });
    });

    window.opener.jQuery('select').each(function (index) {
        var text = jQuery(this).find('option:selected').text();
        var id = jQuery(this).attr("id");
        var val = jQuery(this).val();
        jQuery(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
//        window.opener.jQuery('#' + id + ' option:[value="' + val + '"]').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
    });

    window.opener.jQuery("#inputForm :input[type=text]").each(function (index) {
        jQuery(this).attr("value", jQuery(this).val());
    });

    window.opener.jQuery("#inputForm :input[type=number]").each(function (index) {
        jQuery(this).attr("value", jQuery(this).val());
    });
    window.opener.jQuery("textarea").each(function (index) {
        jQuery(this).text(jQuery(this).val());
    });


    window.opener.jQuery("select").css({
        "border": "none",
        "background": "#fff",
        "pointer-events": "none",
        "box-shadow": "none",
        "-webkit-appearance": "none",
        "-moz-appearance": "none",
        "appearance": "none"
    });

    window.opener.jQuery("input[type='file']").prop("disabled", true);

    window.opener.jQuery("fieldset").css({"display": "block"});
    window.opener.jQuery(".actions").css({"display": "none"});
    window.opener.jQuery(".steps").css({"display": "none"});
    window.opener.jQuery(".draft").css({"display": "none"});
    window.opener.jQuery(".title ").css({"display": "none"});
    // window.opener.jQuery("#payment_panel").css({"margin-top" : "20px"});
    window.opener.jQuery(".card").css({"margin-top" : "10px"});
    // window.opener.jQuery("#reqDoc").removeAttr('style');
    //    window.opener.jQuery("select").prop('disabled', true);
    document.getElementById("previewDiv").innerHTML = window.opener.document.getElementById("inputForm").innerHTML;


    // Declaration Q
    const declarationQList = ['declaration_q1', 'declaration_q2', 'declaration_q3', 'declaration_q4'];
    declarationQList.forEach(qItem => {
        const qValue = window.opener.document.querySelector(`input[name="${qItem}"]:checked`);
        if (qValue && qValue.value) {
            let declarationQ3Img = '';
            if (qItem === 'declaration_q3' && qValue.value === "Yes") {
                const declarationQ3Images = window.opener.document.querySelector(`#declaration_q3_images`).value;
                declarationQ3Img = declarationQ3Images ? declarationQ3Images.split("\\")[2] : '';
                declarationQ3Img = (declarationQ3Img !== "") ? declarationQ3Img : window.opener.jQuery("#declaration_q3_images_preview").data('image-preview');
                declarationQ3Img = `<span style="line-height: 35px">${declarationQ3Img}</span>`;
            }
            document.getElementById(qItem).innerHTML = `<span style="line-height: 5px">${qValue.value}</span><br>${declarationQ3Img}`;
        }
    });

    // payment panel
    const fieldName = 'payment_type';
    let paymentName = null;
    let checkedElem = window.opener.document.querySelector(`input[name="${fieldName}"]:checked`);
    if (checkedElem) {
        let paymentType =  checkedElem.value;
        if(paymentType === 'online_payment') paymentName = 'Online Payment';
        else if(paymentType === 'pay_order') paymentName = 'Pay Order';

        document.getElementById('paymentType').innerHTML = `<span>${paymentName}</span>`;
        const elementIdList = ['pay_order_date', 'bg_expire_date', 'pay_order_contact_email'];


        for (const elem of elementIdList) {
            const fieldEle = window.opener.document.getElementById(elem);
            if(!fieldEle) continue ;
            document.getElementById(`${elem}_preview`).innerHTML = fieldEle.value;
        }

        const payOrderCopyEle = window.opener.document.getElementById('pay_order_copy_url_base64');
        if(payOrderCopyEle){
            document.getElementById('pay_order_copy_preview').innerHTML = `<a href="${payOrderCopyEle.value}" target="_blank">Pay Order Copy<a/>`;
        }

        const bgCopyEle = window.opener.document.getElementById('bg_copy_url_base64');
        if(bgCopyEle){
            document.getElementById('bg_copy_url_base64_preview').innerHTML = `<a href="${bgCopyEle.value}" target="_blank">Bank Guarantee Copy</a>`;
        }

        const payOrderCopyURLDom = document.getElementById('pay_order_exist_link');
        const bankGuaranteeCopyUrlDom = document.getElementById('bank_guarantee_exist_link');
        if (payOrderCopyURLDom) payOrderCopyURLDom.style.display = 'none';
        if (bankGuaranteeCopyUrlDom) bankGuaranteeCopyUrlDom.style.display = 'none';
    }

    //start document pdf show preview mode

    const save_file = window.opener.document.getElementsByClassName('save_file');
    if(save_file){
        for (let i = 0; i < save_file.length; i++) {
            var classValue = save_file[i].getAttribute('class');
            var classArray = classValue.split(' '); // Split the classValue into an array of classes
            var secondClass = classArray[1]; // Access the second class by index
            var secondClassArray = secondClass.split('_');
            var save_file_id = secondClassArray[2];
            const hrefValueID = jQuery('.saved_file_'+save_file_id+' .documentUrl').attr('href');
            // var hrefValueID = jQuery('#validate_field_'+save_file_id).val();
            if(hrefValueID){
                // hrefValueID = "/uploads/"+hrefValueID;
                document.getElementById('pdf_show_preview_'+save_file_id).innerHTML = `<a style="color: #fff;background-color: #007bff;box-shadow: none;font-weight: 400;display: inline-block;padding: 0.125rem 0.25rem;font-size: 0.75rem;line-height: 1.5;border-radius: 0.15rem;user-select: none;border: 1px solid transparent;text-decoration: none;" href="${hrefValueID}" target="_blank">Open File</a>`;
            }
        }
    }

    //end document pdf show preview mode

    //Hide Image flag in mobile number
    let hideMobileFlag = document.querySelectorAll('.input-group-prepend');
    hideMobileFlag.forEach(item => {
        item.style.display = 'none';
    })

    //Hide calender icon
    let removeCalenderIcon = document.querySelectorAll('.fa-calendar');
    removeCalenderIcon.forEach(item => {
        item.parentElement.style.display = 'none';
    })
    //   JavaScript Document
    function printThis(ob) {
        print();
    }

    jQuery('#showPreview').remove();
    jQuery('#save_btn').remove();
    jQuery('#save_draft_btn').remove();
    jQuery('.stepHeader,.calender-icon,.pss-error').remove();
    jQuery('.required-star').removeClass('required-star');
    jQuery('input[type=hidden]').remove();
    jQuery('.panel-orange > .panel-heading').css('margin-bottom', '10px');
    //    jQuery('.input-group-addon').css({"visibility": "hidden"});
    jQuery('.hiddenDiv').css({"visibility": "hidden"});
    jQuery('.datetimepicker4 .input-group-append').css({"display": "none"});
    jQuery('#invalidInst').html('');
    //    jQuery("#docTabs").tab('show');
    jQuery('#previewDiv .btn').each(function () {
        jQuery(this).replaceWith("");
    });

    jQuery('#application_form :input').attr('disabled', true);
    document.getElementById("accept_terms").checked = true;


    jQuery('#previewDiv').find('input:not([type=radio][type=hidden][type=file][name=acceptTerms]), textarea').each(function () {
        var allClass = jQuery(this).attr('class');
        if ((typeof allClass != 'undefined') && allClass.match("onlyNumber")) {
            if (allClass.match("nocomma")) {
                var thisVal = this.value;
            } else {
                var thisVal = commaSeparateNumber(this.value);
            }

        } else {
            var thisVal = this.value;
        }

        if (this.id == 'accept_terms') {
            return false;
        }
        jQuery(this).replaceWith('<span style="line-height: 30px">' + thisVal + '</span>');
    });

    jQuery('#previewDiv').find('input [type=file]').each(function () {
        jQuery(this).replaceWith("<span>" + this.value + "</span>");
    });

    jQuery('#previewDiv #accept_terms').attr("onclick", 'return false').prop("checked", true).css('margin-left', '-8px');
    jQuery('#previewDiv').find('input [type=radio]').each(function () {
        jQuery(this).attr('disabled', 'disabled');
    });


    jQuery("select").replaceWith(function () {
        return jQuery(this).find('option:selected').text();
    });

    jQuery(".hashs").replaceWith("");


    function CloseMe() {
        window.opener.jQuery("fieldset").css({"display": "none"});
        window.opener.jQuery(".actions").css({"display": "block"});
        window.opener.jQuery(".steps").css({"display": "block"});
        window.opener.jQuery(".draft").css({"display": "block"});
        window.opener.jQuery(".title ").css({"display": "block"});
        window.opener.jQuery('.input-group-addon').css({"visibility": "visible"});
        window.opener.jQuery("#application_form-p-3").css({"display": "block"});
        window.opener.jQuery(".last").addClass('current');
        window.opener.jQuery('body').css({"display": "block"});
        window.opener.jQuery("select").css({
            "border": '1px solid #ccc',
            "background": '#fff',
            "pointer-events": 'inherit',
            "box-shadow": 'inherit',
            "-webkit-appearance": 'menulist',
            "-moz-appearance": 'menulist',
            "appearance": 'menulist'
        });
        window.opener.jQuery("select").css({
            "border": '1px solid #ccc',
            "background": '#fff',
            "pointer-events": 'inherit',
            "box-shadow": 'inherit',
            "-webkit-appearance": 'menulist',
            "-moz-appearance": 'menulist',
            "appearance": 'menulist'
        });
        window.opener.jQuery("input[type='file']").prop("disabled", false);
        window.close();
    }

    jQuery('.cr-boundary,.cr-slider-wrap').css({
        "display": 'none'
    });

</script>
