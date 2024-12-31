<div class="ad_desk_form form-group">
    <div class="col-md-12">
        <div class="col-md-12 panel panel-default">
            <div class="panel-body">
                <label for="reg_cer_chk"></label>
                    <div class="row mx-auto">
                        <table class="table table-responsive">
                            <tr>
                                <td><label style="margin-right: 10px; min-width: 32%;">Evaluation Report Upload</label></td>
                                <td><input type="file" name="dd_file_1" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                            </tr>
                            @if($process_type_id != 5)
                                <tr>
                                    <td><label style="margin-right: 10px; min-width: 32%;">Ministry Approval Upload</label></td>
                                    <td><input type="file" name="dd_file_3" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                                    <td><label style="margin-right: 5px; min-width: 32%;">Commission Meeting Minutes Upload</label></td>
                                    <td><input type="file" name="dd_file_2" id="reg_cer_chk_yes" accept="application/pdf" onchange="createObjUrl(event, '', true)" class="btn-sm reg_cer_chk" required></td>
                                </tr>
                            @endif
                        </table>
                    </div>

{{--                <div class="row d-flex">--}}
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
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>
