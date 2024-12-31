function loadPaymentPanel(app_id = '', process_type_id, payment_step_id, target_element_id, contact_name, contact_email, contact_phone, contact_address, unfixed_amount_array) {

    // Reset the html content
    document.getElementById(target_element_id).innerHTML = '';

    if (typeof unfixed_amount_array !== 'object' && unfixed_amount_array == null) {
        alert('Error: Unfixed amounts variable should be an object, ' + typeof unfixed_amount_array + ' given');
    }

    const form_data = new FormData();
    form_data.append('app_id', app_id);
    form_data.append('process_type_id', process_type_id);
    form_data.append('payment_step_id', payment_step_id);
    form_data.append('contact_name', contact_name);
    form_data.append('contact_email', contact_email);
    form_data.append('contact_phone', contact_phone);
    form_data.append('contact_address', contact_address);
    form_data.append('unfixed_amount_array', JSON.stringify(unfixed_amount_array));

    $.ajax({
        url: '/spg/payment-panel',
        dataType: 'json',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (response) {
            if (response.status === true) {
                document.getElementById(target_element_id).innerHTML = response.data.html;
            } else {
                alert(response.message);
            }
        }
    });
}

let is_payment_info_loaded = false;
function loadPaymentInfo(process_type_id, ref_id, content_div_id, content_loader_id) {
    if (!is_payment_info_loaded) {
        $.ajax({
            url: "/spg/payment-view/" + process_type_id + "/" + ref_id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $("#" + content_div_id).html(response.response);
                $("#" + content_loader_id).hide();
                is_payment_info_loaded = true;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
    }
}
