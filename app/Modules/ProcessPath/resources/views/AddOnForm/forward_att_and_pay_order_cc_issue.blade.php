<div class="ad_desk_form form-group">
    <div class="col-md-12">
        <div class="col-md-12 panel panel-default">
            @if(!empty($payment_info) && ($payment_info->payment_type != 'online_payment'))
                @if(isset($payment_info->is_pay_order_verified) && $payment_info->is_pay_order_verified === 1)
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
                        <label>Pay order payment is verified: </label>
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
            <div class="panel-body">
                <label for="reg_cer_chk"></label>
                <div class="row mx-auto">
                    <table class="table table-responsive">
                        <tr>
                            <td> <label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label></td>
                            <td><input type="file" name="dd_file_1" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                        </tr>
                    </table>
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_1" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
                </div>

                <!--
                <div class="col-md-12" style="padding-top: 15px;">
                    <label>
                        <div class="col-md-12"> Evaluation Report Upload </div>
                        <div class="col-md-12">
                            <input type="file" name="dd_file_1" id="reg_cer_chk_yes" class="reg_cer_chk" required>
                        </div>
                    </label>
                </div>
                <div class="col-md-12" style="padding-top: 15px;">
                    <label>
                        <div class="col-md-12"> Commission Meeting Minutes Upload </div>
                        <div class="col-md-12">
                            <input type="file" name="dd_file_2" id="reg_cer_chk_yes" class="reg_cer_chk" required>
                        </div>
                    </label>
                </div>
                <div class="col-md-12" style="padding-top: 15px;">
                    <label>
                        <div class="col-md-12"> Ministry Approval Upload </div>
                        <div class="col-md-12">
                            <input type="file" name="dd_file_3" id="reg_cer_chk_yes" class="reg_cer_chk" required>
                        </div>
                    </label>
                </div>
                -->
            </div>
        </div>
    </div>
</div>
