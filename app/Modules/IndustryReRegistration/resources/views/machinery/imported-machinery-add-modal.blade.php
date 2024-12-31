{{--Machinary locally collected modal--}}
{!! Form::open(array('url' => url('industry-re-registration/imported-machinery/store'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'local_machinery', 'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

{!! Form::hidden('app_id', $app_id ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}

<div class="modal-header">
    <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
    <h4 id="local_machine_modal_head" class="modal-title"
        style="color: #452A73; font-size: 14px">{!!trans('CompanyProfile::messages.machinery_information')!!}</h4>
    <h4 id="local_machineCollect_modal_head" class="modal-title" style="font-size: 16px"
        hidden>{!!trans('CompanyProfile::messages.imported')!!}</h4>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    {{--Choose option--}}
    <div id="local_machine_choose_input" class="text-center">
        <button id="local_machine_manual_input" type="button" class="btn"
                style="color: white; background: #2A8D46; width: 100px;">
            <i class="fa fa-pencil"></i> {!!trans('CompanyProfile::messages.manually')!!}
        </button>
        <br>
        {!!trans('CompanyProfile::messages.or')!!}
        <br>
        <button id="local_machine_file_upload" type="button" class="btn"
                style="color: white; background: #DFA40D; width: 100px;">
            <i class="fa fa-file-excel-o"></i> {!!trans('CompanyProfile::messages.browse')!!}
        </button>
    </div>

    {{--Add table--}}
    <table id="local_machine_manual_input_table"
           class="table table-bordered dt-responsive"
           cellspacing="0" width="100%" hidden>
        <thead>
        <tr style="background: #F7F7F7;">
            <th class="text-center">{!!trans('CompanyProfile::messages.machinery_name')!!}</th>
            <th class="text-center">{!!trans('CompanyProfile::messages.n_number')!!}</th>
            <th class="text-center">{!!trans('CompanyProfile::messages.price_taka')!!}</th>
        </tr>
        </thead>
        <tbody>
        <tr class="text-center">
            <td>
                {!! Form::text('imported_machinery_nm', '', ['class' => 'form-control input-md', 'placeholder'=> trans('CompanyProfile::messages.machinery_name'), 'id'=> 'imported_machinery_name']) !!}
                {!! $errors->first('imported_machinery_nm','<span class="help-block">:message</span>') !!}
            </td>
            <td>
                {!! Form::text('imported_machinery_qty', '', ['class' => 'form-control input-md onlyNumber input_ban', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'imported_machinery_number']) !!}
                {!! $errors->first('imported_machinery_qty','<span class="help-block">:message</span>') !!}
            </td>
            <td>
                {!! Form::text('imported_machinery_price', '', ['class' => 'form-control input-md onlyNumber input_ban', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'imported_machinery_price']) !!}
                {!! $errors->first('imported_machinery_price','<span class="help-block">:message</span>') !!}
            </td>
        </tr>
        </tbody>
    </table>

    <div id="local_machine_file_upload_div" hidden>
        <div class="row text-center" style="margin-bottom: 15px">
            <a href="{!! url('/csv-upload/sample/sample.xlsx') !!}"
               style="font-size: 16px;"><i class="fa fa-file-excel-o"></i> নমুনা ফাইল</a>
        </div>
        <div class="sign_div" style="margin: 0 10px">

            <div style="text-align: center;">
                <div class="signature-upload">
                    <div class="text-center">
                        <i class="fa fa-3x fa-file-excel-o" style="color: #258DFF"></i>
                        <span id="file_name"></span>
                        <p style="font-size: 16px;">
                            Drop your excel file scan copy here or <strong
                                    style="color: #259BFF">Browse</strong>
                        </p>
                        <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.xlsx/ .csv .xls | Maximum 5 MB]
                                                                </span>
                    </div>
                </div>
                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" type="file" name="file_upload"
                       id="excel_upload" class="signature-upload-input" onchange="handleLocalFileUpload(this)">
            </div>
        </div>
        <div class="alert alert-danger" style="font-size:13px;">
            <strong>Note:</strong> Upload only .csv, .xls or .xlsx file. Use the sample to upload,  otherwise data
            will be mismatched. To follow the given sample file, you can <a href="{!! url('/csv-upload/sample/sample.xlsx') !!}" title="Sample file">
                <strong>click here</strong></a>.
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm close_modal" data-dismiss="modal"
            style="float: left;">Close
    </button>

    <div id="local_save_btn" style="float: right" hidden>
        <button type="submit" id="local_save_btn" class=" local_save_btns btn btn-primary btn-sm"><i class="fa fa-save"></i>
            Save
        </button>
    </div>
    <div id="local_action_previous" style="float: right; margin-right: 8px" hidden>
        <button type="button" id="local_previous_btn" class="btn btn-info btn-sm local_previous_btn">
            Previous
        </button>
    </div>
</div>
{!! Form::close() !!}


<script src="{{ mix("assets/scripts/jquery.validate.min.js") }}"></script>
<script>
    $(document).ready(function () {

        // Local machine info modal script start
        $('#local_machine_manual_input').on('click', function () {
            $('#local_machine_modal_head').hide()
            $('#local_machine_choose_input').hide()
            $('#local_machineCollect_modal_head').show()
            $('#local_machine_manual_input_table').show()
            $('#local_save_btn').show()
            $('#local_action_previous').show();

            $('.local_save_btns').prop('id','local_save_btn');
            $('.local_save_btns').prop('type','submit');
        })

        $('.local_previous_btn').on('click', function () {
            $('#local_machine_manual_input_table').find('input:text').val('');
            $('#local_machineCollect_modal_head').hide()
            $('#local_machine_manual_input_table').hide()
            $('#local_machine_file_upload_div').hide()
            $('#local_save_btn').hide()
            $('#local_action_previous').hide()
            $('#local_machine_modal_head').show()
            $('#local_machine_choose_input').show()
        })

        $('#local_machine_file_upload').on('click', function () {
            $('#local_machine_choose_input').hide()
            $('#local_machine_file_upload_div').show()
            $('#local_save_btn').show()
            $('#local_action_previous').show()

            $('.local_save_btns').prop('id','attachmentFile');
            $('.local_save_btns').prop('type','button');
        })

        $('.close_modal').on('click', function () {
            $('#local_machine_manual_input_table').find('input:text').val('')
            $('#local_machine_file_upload_div').find('input:file').val('');
            $('#local_machine_manual_input_table').hide()
            $('#local_machine_details_table').hide()
            $('#local_save_btn').hide()
            $('#local_action_previous').hide()
            $('#local_machine_file_upload_div').hide()
            $('#local_machineCollect_modal_head').hide()
            $('#local_machine_modal_head').show()
            $('#local_machine_choose_input').show()

            LoadListOfImportedMachinery()
        })
        // Local machine info modal script end

        $("#local_machinery").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        var form = $("#local_machinery"); //Get Form ID
        var url = form.attr("action"); //Get Form action
        var type = form.attr("method"); //get form's data send method
        var info_err = $('.errorMsg'); //get error message div
        var info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {

            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function (msg) {
                    $("#Duplicated jQuery selector").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#Duplicated jQuery selector").prop('disabled', true); // disable button
                },
                success: function (data) {
                    //==========validation error===========//
                    if (data.success == false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            // window.location.href = data.link;
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#Duplicated jQuery selector").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });

    function handleLocalFileUpload(input) {
        var file = $('#excel_upload')[0].files[0].name;
        $('#file_name').html(file);
    }

    $(document).off('click').on('click', '#attachmentFile', function() {
        var app_id = $('#app_id').val();
        var file_data = $('#excel_upload').prop('files')[0];
        // console.log(file_data)
        var form_data = new FormData();
        form_data.append('file_upload', file_data);
        form_data.append('app_id', app_id);
        form_data.append('type', "machinery");
        // console.log(form_data);
        $.ajax({
            url: '/client/industry-re-registration/imported-machinery/attachment', // <-- point to server-side PHP script
            dataType: 'json',  // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                var info_err = $('.errorMsg'); //get error message div
                var info_suc = $('.successMsg'); //get success message div
                // console.log(data.success)
                //==========if data is saved=============//
                if (data.success == true) {
                    info_suc.hide().empty();
                    info_suc.removeClass('hidden').html(data.status);
                    info_suc.slideDown('slow');
                    info_suc.delay(2000).slideUp(800, function () {
                        // window.location.href = data.link;
                        $(".close_modal").trigger('click');
                    });
                    form.trigger("reset");

                }
                //=========if data already submitted===========//
                if (data.error == true) {
                    info_err.hide().empty();
                    info_err.removeClass('hidden').html(data.status);
                    info_err.slideDown('slow');
                    info_err.delay(1000).slideUp(800, function () {
                        $("#Duplicated jQuery selector").html('Submit');
                        $("#Duplicated jQuery selector").prop('disabled', false);
                    });
                }
            }
        });
    });
</script>
<script type="text/javascript" src="{{ asset("assets/scripts/custom.min.js") }}"></script>