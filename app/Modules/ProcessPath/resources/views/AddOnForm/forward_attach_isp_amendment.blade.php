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
                        <?php }?>
                        <tr>
                            @if($process_type_id != 5)
                                <td><label style="margin-right: 5px; min-width: 32%;">Commission Meeting Minutes Upload</label></td>
                                <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
