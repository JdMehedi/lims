@if(!empty($payment_info) && ($payment_info->payment_type != 'online_payment'))
    <div class="ad_desk_form form-group">
        <div class="col-md-12">
            <div class="col-md-12 panel panel-default">
                <div class="panel-body">
                    <label for="reg_cer_chk"></label>
                    @if(!empty($payment_info))
                    <div class="d-flex" style="column-gap: 20px; pointer-events: none; opacity: 0.6; <?php echo env('IS_MOBILE') ? 'margin-top: 30px;' : '' ?>">
                        <label>Pay order payment is verified: </label>
                            <div>
                                <input type="radio" name="pay_order" id="yes" class="btn-sm" value="YES" {{ $payment_info->is_pay_order_verified === 1 ? "checked":"" }} >
                                <label for="yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" name="pay_order" id="no" class="btn-sm" value="NO" {{ $payment_info->is_pay_order_verified === 0 ? "checked":"" }} >
                                <label for="no">No</label>
                            </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
