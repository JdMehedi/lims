<div class="ad_desk_form form-group">
    <div class="col-md-12">
        <div class="col-md-12 panel panel-default">
            <div class="panel-body">
                <label for="reg_cer_chk"></label>
                <div class="row mx-auto">
                    <table class="table table-responsive">
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;" class="required-star">Evaluation Report Upload</label></td>
                            <td><input type="file" name="dd_file_1" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php if(!env('IS_MOBILE')){ ?>
                            <td><label style="margin-right: 10px; min-width: 32%;">Inspection Report Upload</label></td>
                            <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk"></td>
                            <?php }?>
                        </tr>
                        <?php if(env('IS_MOBILE')){ ?>
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;">Inspection Report Upload</label></td>
                            <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk"></td>
                        </tr>
                        <?php }?>

                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;" class="required-star">Commission Meeting Minutes Upload</label></td>
                            <td><input type="file" name="dd_file_3" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php if(!env('IS_MOBILE')){ ?>
                            <td><label style="margin-right: 10px; min-width: 32%;" class="required-star">Ministry Approval Upload</label></td>
                            <td><input type="file" name="dd_file_4" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php }?>
                        </tr>
                        <?php if(env('IS_MOBILE')){ ?>
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;" class="required-star">Ministry Approval Upload</label></td>
                            <td><input type="file" name="dd_file_4" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                        </tr>
                        <?php }?>
                    </table>
                </div>

{{--                <div class="row d-flex">--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;" class="required-star">Evaluation Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_1" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Inspection Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_2" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;" class="required-star">Commission Meeting Minutes Upload</label>--}}
{{--                        <input type="file" name="dd_file_3" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;" class="required-star">Ministry Approval Upload</label>--}}
{{--                        <input type="file" name="dd_file_4" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>
