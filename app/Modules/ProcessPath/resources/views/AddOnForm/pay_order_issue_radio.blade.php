<style>
    .tox-tinymce {
        width: 1270px !important;
    }
    @media (max-width: 1000px) {
        .tox-tinymce {
            width: 900px !important;
            margin: auto;
        }
    }
    @media (max-width: 768px) {
        .tox-tinymce {
            width: 700px !important;
            margin: auto;
        }
    }
    @media (max-width: 500px) {
        .tox-tinymce {
            width: 450px !important;
            margin: auto;
        }
    }
</style>
<div class="ad_desk_form form-group">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <label for="reg_cer_chk"></label>
                @if(!empty($payment_info) && ($payment_info->payment_type != 'online_payment'))
                    @if($payment_info->is_pay_order_verified === 1)
                        <div class="d-flex" style="column-gap: 20px; pointer-events: none; opacity: 0.6; <?php echo env('IS_MOBILE') ? 'margin-top: 30px;' : '' ?>">
                            <label>Pay order payment is verified: </label>
                            <div>
                                <input type="radio" name="pay_order" id="yes" class="btn-sm" value="YES" checked>
                                <label for="yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="pay_order" id="no" class="btn-sm" value="NO">
                                <label for="no">No</label>
                            </div>
                        </div>
                    @else
                        <div class="d-flex" style="column-gap: 20px; <?php echo env('IS_MOBILE') ? 'margin-top: 30px;' : '' ?>">
                            <label>Pay order payment is verified:</label>
                            <div>
                                <input type="radio" name="pay_order_verification" id="yes" class="btn-sm" value="YES" required>
                                <label for="yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="pay_order_verification" id="no" class="btn-sm" value="NO">
                                <label for="no">No</label>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

