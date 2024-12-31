<div class="ad_desk_form form-group">
    <div class="col-md-12">
        <div class="col-md-12 panel panel-default">
            <div class="panel-body">
                <label for="reg_cer_chk"></label>
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
                <div class="row mx-auto">
                    <table class="table table-responsive">
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label></td>
                            <td><input type="file" name="dd_file_1" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php if(!env('IS_MOBILE')){ ?>
                                @if($process_type_id != 5)
                                <td><label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label></td>
                                <td><input type="file" name="dd_file_3" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                                @endif
                            <?php } ?>
                        </tr>
                        <?php if(env('IS_MOBILE')){ ?>
                        <tr>
                            @if($process_type_id != 5)
                                <td><label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label></td>
                                <td><input type="file" name="dd_file_3" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            @endif
                        </tr>
                        <?php } ?>
                        <tr>
                            @if($process_type_id != 5)
                            <td><label style="margin-right: 5px; min-width: 32%;">Commission Meeting Minutes Upload</label></td>
                            <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            @endif
                        </tr>
                    </table>

{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_1" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    @if($process_type_id != 5)--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label>--}}
{{--                        <input type="file" name="dd_file_3" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-7 mb-3 ">--}}
{{--                        <label style="margin-right: 5px; min-width: 32%;">Commission Meeting Minutes Upload</label>--}}
{{--                        <input type="file" name="dd_file_2" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    @endif--}}
                </div>
            </div>
        </div>
    </div>
</div>
