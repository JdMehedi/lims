{!! Form::open(array('url' => url('/settings/document-v2/store'),'method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'documentCreateForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header">

    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-stop"></i> Create Document</h4>
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
    </button>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('name','Document name: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-9">
                    {!! Form::text('name', null, ['class'=>'form-control required input-sm','id'=> 'name','placeholder'=>'Document name']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('min_size','Minimum size(KB): ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::number('min_size', '05', ['class'=>'form-control required input-sm','placeholder'=>'Minimum size', 'id'=>'min_size', 'readonly'=>true]) !!}
                        <span class="btn input-group-addon" type="button" onclick="editSize(this)">
                            <span class="fa fa-edit"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('max_size','Maximum size(KB): ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::number('max_size', '1024', ['class'=>'form-control required input-sm','placeholder'=>'Maximum size', 'id'=>'max_size', 'readonly'=>true]) !!}
                        <span class="btn input-group-addon" type="button" onclick="editSize(this)">
                            <span class="fa fa-edit"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('simple_file','Simple File',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::file('simple_file', ['class' => 'form-control input-sm', 'id' => 'simple_file']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('status','Status',['class'=>'text-left required-star col-md-3']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('status', 1, true, ['class'=>'required', 'id' => 'doc_n_status_active']) !!}
                        Active
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('status', 0, false, ['class'=>'required', 'id' => 'doc_n_status_inactive']) !!}
                        In-active
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-11">
            <div class="form-group col-md-12 row">
                {!! Form::label('company type','Company Type',['class'=>'text-left required-star col-md-3']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('company_doc_type', 2, false, ['class'=>'required', 'id' => 'doc_n_company_type_proprietorship ']) !!}
                        Proprietorship
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('company_doc_type', 1, true, ['class'=>'required', 'id' => 'doc_n_company_type_others']) !!}
                        Others
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('company_doc_type', 3, false, ['class'=>'required', 'id' => 'doc_n_company_type_both']) !!}
                        Both
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer" style="display: block;">
    <div class="float-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="float-right">
        @if(ACL::getAccsessRight('settings','A'))
            <button type="submit" class="btn btn-primary" id="block_create_btn" name="actionBtn" value="draft">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


<script>
    $(document).ready(function () {
        $("#documentCreateForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        const form = $("#documentCreateForm"); //Get Form ID
        const url = form.attr("action"); //Get Form action
        const type = form.attr("method"); //get form's data send method
        const info_err = $('.errorMsg'); //get error message div
        const info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {

            const simpleFileInput = $("#simple_file")[0];
            let formData = new FormData();
            formData.append('name', $("#name").val());
            formData.append('min_size', $("#min_size").val());
            formData.append('max_size', $("#max_size").val());
            formData.append("simple_file", simpleFileInput.files[0]);
            formData.append("status", $(':radio[name=status]:checked').val());
            formData.append("company_doc_type", $(':radio[name=company_doc_type]:checked').val());

            $.ajax({
                type: type,
                url: url,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function (msg) {
                    console.log("before send");
                    $("#block_create_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#block_create_btn").prop('disabled', true); // disable button
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
                            $("#block_create_btn").html('Submit');
                            $("#block_create_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            window.location.href = "{{URL::to('/').'/settings/document-v2' }}";
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#block_create_btn").html('Submit');
                            $("#block_create_btn").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    const errors = data.responseJSON;
                    $("#block_create_btn").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }

    });

    function editSize(e) {
        if ($(e).children('span').hasClass('fa fa-edit')) {
            $(e).children('span').removeClass('fa fa-edit').addClass('fa fa-ban');
            $(e).prev('input').attr('readonly', false);
        } else {
            $(e).children('span').removeClass('fa fa-ban').addClass('fa fa-edit');
            $(e).prev('input').attr('readonly', true);
        }

    }
</script>
