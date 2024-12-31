{{--Machinary locally collected modal--}}
{!! Form::open(array('url' => url('industry-re-registration/imported-machinery/store'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'local_machinery', 'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

{!! Form::hidden('machine_id', \App\Libraries\Encryption::encodeId($importedMachine->id) ,['class' => 'form-control input-md', 'id'=>'machine_id']) !!}
{!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($importedMachine->app_id) ,['class' => 'form-control input-md', 'id'=>'app_id']) !!}

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

    {{--Add table--}}
    <table id="local_machine_manual_input_table"
           class="table table-bordered dt-responsive"
           cellspacing="0" width="100%">
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
                {!! Form::text('imported_machinery_nm', $importedMachine->machinery_nm, ['class' => 'form-control input-md', 'placeholder'=> trans('CompanyProfile::messages.machinery_name'), 'id'=> 'imported_machinery_name']) !!}
                {!! $errors->first('imported_machinery_nm','<span class="help-block">:message</span>') !!}
            </td>
            <td>
                {!! Form::text('imported_machinery_qty', $importedMachine->machinery_qty, ['class' => 'form-control input-md onlyNumber input_ban', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'imported_machinery_number']) !!}
                {!! $errors->first('imported_machinery_qty','<span class="help-block">:message</span>') !!}
            </td>
            <td>
                {!! Form::text('imported_machinery_price', $importedMachine->machinery_price, ['class' => 'form-control input-md onlyNumber input_ban', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'imported_machinery_price']) !!}
                {!! $errors->first('imported_machinery_price','<span class="help-block">:message</span>') !!}
            </td>
        </tr>
        </tbody>
    </table>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm close_modal" data-dismiss="modal"
            style="float: left;">Close
    </button>

    <div id="local_save_btn" style="float: right">
        <button type="submit" id="local_save_btn" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>
            Save
        </button>
    </div>
</div>
{!! Form::close() !!}


<script src="{{ mix("assets/scripts/jquery.validate.min.js") }}"></script>
<script>
    $(document).ready(function () {

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
</script>
<script type="text/javascript" src="{{ asset("assets/scripts/custom.min.js") }}"></script>