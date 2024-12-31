<div class="ad_desk_form form-group">
    <div class="col-md-12 justify-content-center">
        <div class="col-md-12 panel panel-default justify-content-center">
            <div class="panel-body" style="padding:30px;">
                <label for="reg_cer_chk"></label>

                <div class="row mx-auto">
                    <table class="table table-responsive">
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label></td>
                            <td><input type="file" name="dd_file_1" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php if(!env('IS_MOBILE')){ ?>
                            <td><label style="margin-right: 10px; min-width: 32%;">Commission Meeting Minutes Upload</label></td>
                            <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php }?>
                        </tr>
                        <?php if(env('IS_MOBILE')){ ?>
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;">Commission Meeting Minutes Upload</label></td>
                            <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td><label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label></td>
                            <td><input type="file" name="dd_file_3" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php if(!env('IS_MOBILE')){ ?>
                            <td><label style="margin-right: 0px; min-width: 47%;">Inspection Report Upload</label></td>
                            <td><input type="file" name="dd_file_4" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            <?php }?>
                        </tr>
                        <?php if(env('IS_MOBILE')){ ?>
                        <tr>
                            <td><label style="margin-right: 0px; min-width: 47%;">Inspection Report Upload</label></td>
                            <td><input type="file" name="dd_file_4" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                        </tr>
                        <?php }?>
                    </table>
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_1" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Commission Meeting Minutes Upload</label>--}}
{{--                        <input type="file" name="dd_file_2" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label>--}}
{{--                        <input type="file" name="dd_file_3" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6 mb-3 ">--}}
{{--                        <label style="margin-right: 0px; min-width: 47%;">Inspection Report Upload</label>--}}
{{--                        <input type="file" name="dd_file_4" id="reg_cer_chk_yes" class="btn-sm reg_cer_chk" required>--}}
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