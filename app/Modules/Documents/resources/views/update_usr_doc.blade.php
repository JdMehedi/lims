{!! Form::open(array('url' => url('/documents/update-user-document/' . $encoded_doc_id),'method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'userDocUploadForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header bg-green">
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-upload"></i> Upload document</h4>
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
    </button>
</div>

<div class="modal-body">
    <input type="hidden" name="selected_file" id="selected_file"/>
    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
    <input type="hidden" name="isRequired" id="isRequired"/>


    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-group col-md-12 row">
                {!! Form::label('name','Document name: ',['class'=>'col-md-4']) !!}
                <div class="col-md-8">
                    {{ $document_info->name }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-group col-md-12 row">
                {!! Form::label('attachment','Attachment',['class'=>'required-star col-md-4']) !!}
                <div class="col-md-8">
                    <input name="attachment" class="form-control required"
                           id="attachment" type="file" accept="application/pdf"
                           onchange="uploadDocument('attachment_preview', this.id, 'attachment', '1')"/>
                    <span class="help-block">Maximum file size {{ ($document_info->max_size > 1023) ? round($document_info->max_size/1024, 2) .' MB' : $document_info->max_size.' KB' }}. File type: PDF</span>
                    <a href="https://www.adobe.com/acrobat/online/jpg-to-pdf.html" target="_blank" style="font-size: 12px">{{trans("Documents::messages.click_to_convert")}}</a>

                    <div id="attachment_preview">
                        <input type="hidden"
                               value="1"
                               id="attachment"
                               name="attachment"
                               class="required"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="output_pdf_page_count" id="output_pdf_page_count" value="0">
</div>

<div class="modal-footer" style="display: block;">
    <div class="float-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="float-right">
        <button type="submit" class="btn btn-primary" id="block_create_btn" name="actionBtn" value="draft">
            <i class="fa fa-chevron-circle-right"></i> Save
        </button>
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


<script>
    var pagecount = 0;
    var ready = false;

    function readFile(file, onLoadCallback) {
        var reader = new FileReader();
        reader.onloadend = onLoadCallback;
        reader.readAsText(file);
    }
    $('#attachment').click(function () {
        $('#output_pdf_page_count').val(0);
    });

    function uploadDocument(targets, id, vField, isRequired) {
        document.getElementById(targets).style.color = "red";
        $("#" + targets).html('Uploading....');
        $("#block_create_btn").prop('disabled', true); // disable button
        const inputFile = $("#" + id).val();
        var file_size = $("#" + id).prop('files')[0].size;
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        if (file_size / 1024 > '{{$document_info->max_size}}' || (file_size / 1024 < '{{$document_info->min_size}}')) {
            alert("Please upload files between " + '{{$document_info->min_size}}' + " KB - " + '{{round($document_info->max_size/1024)}}' + " MB. Thanks!!");
            $("#" + id).val('');
            return false;
        }else{
            var obj = $("#" + id);
            readFile(obj.prop('files')[0], function (e) {
                // use result in callback...
                pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
                $('#output_pdf_page_count').val(pagecount);
                ready = true;
            });
            var check = function () {
                if (ready === true) {
                    var countPage = $('#output_pdf_page_count').val();
                    if (countPage > 0) {
                        if (countPage >= 6) {
                            if (countPage >= 11) {
                                if ((file_size / countPage) / 1024 > 500 || file_size / 1024 < '{{$document_info->min_size}}') {
                                    obj.val('');
                                    alert("Please upload files between " + '{{$document_info->min_size}}' + "-" + 500 + " KB. Per Page Thanks!!");
                                } else {
                                    uploadFile(targets, id, vField, isRequired)
                                }
                            } else {
                                if ((file_size / countPage) / 1024 > 500 || file_size / 1024 < '{{$document_info->min_size}}') {
                                    obj.val('');
                                    alert("Please upload files between " + '{{$document_info->min_size}}' + "-" + 500 + " KB. Per Page Thanks!!");
                                } else {
                                    uploadFile(targets, id, vField, isRequired)
                                }
                            }
                        } else if (countPage > 1) {
                            if ((file_size / countPage) / 1024 > 500 || file_size / 1024 < '{{$document_info->min_size}}') {
                                alert("Please upload files between " + '{{$document_info->min_size}}' + "-" + 500 + " KB. Per Page Thanks!!");
                                obj.val('');
                            } else {
                                uploadFile(targets, id, vField, isRequired)
                            }
                        } else {
                            if (file_size / 1024 > '{{$document_info->max_size}}' || file_size / 1024 < '{{$document_info->min_size}}') {
                                alert("Please upload files between " + '{{$document_info->min_size}}' + "-" + '{{round($document_info->max_size/1024)}}' + " MB. Thanks!!");
                                obj.val('');
                            } else {
                                uploadFile(targets, id, vField, isRequired)
                            }
                        }
                    }
                    ready = false;
                } else {
                    setTimeout(check, 1000);
                }
            }
            check();
        }

    }

    function uploadFile(targets, id, vField, isRequired){
        const inputFile = $("#" + id).val();
        try {

            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            // document.getElementById(targets).style.color = "red";
            const action = '/documents/upload-document';
            const max_size = "{{ $document_info->max_size }}";

            // $("#" + targets).html('Uploading....');
            const file_data = $("#" + id).prop('files')[0];
            const form_data = new FormData();
            form_data.append('max_size', max_size);
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    const fileNameArr = inputFile.split("\\");
                    const l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    const newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    const validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                    $("#block_create_btn").prop('disabled', false);
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            $("#block_create_btn").prop('disabled', false);
        }
    }

    $(document).ready(function () {
        $("#userDocUploadForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        const form = $("#userDocUploadForm"); //Get Form ID
        const url = form.attr("action"); //Get Form action
        const type = form.attr("method"); //get form's data send method
        const info_err = $('.errorMsg'); //get error message div
        const info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit(e) {
            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
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

                            $('#userVerificationModal').modal('hide');
                            $('#myDocumentList').DataTable().destroy();
                            getDocList();
                            // window.location.href = data.link;
                            // window.location.reload();
                        });
                        form.trigger("reset");
                        toastr.success('Document updated successfully!');
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
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });
</script>
{{--<script>--}}
{{--    $('#dms_document').change(function () {--}}
{{--        var element = $(this);--}}

{{--        var mime_type = this.files[0].type;--}}
{{--        if (this.files[0].size / 1024 > '{{$maxfilesize}}' || this.files[0].size / 1024 < 5) {--}}
{{--            alert("Please upload files between " + '{{$minfilesize}}' + "KB -" + '{{round($maxfilesize/1024)}}' + " MB. Thanks!!");--}}
{{--            $(this).val('');--}}
{{--        } else {--}}
{{--            if (mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png') {--}}
{{--                if (this.files[0].size / 1024 > '{{$config_data_array[1]}}' || this.files[0].size / 1024 < '{{$minfilesize}}') {--}}
{{--                    alert("Please upload files between " + '{{$minfilesize}}' + "-" + '{{$config_data_array[1]}}' + " KB. Thanks!!");--}}
{{--                    $(this).val('');--}}
{{--                } else {--}}
{{--                    uploadefile(this);--}}
{{--                }--}}
{{--            } else {--}}


{{--                var file_size = this.files[0].size;--}}


{{--                var obj = this;--}}
{{--                readFile(this.files[0], function (e) {--}}
{{--                    // use result in callback...--}}
{{--                    pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;--}}
{{--                    $('#output_pdf_page_count').val(pagecount);--}}
{{--                    ready = true;--}}
{{--                });--}}
{{--                var check = function () {--}}
{{--                    if (ready === true) {--}}
{{--                        var countPage = $('#output_pdf_page_count').val();--}}
{{--                        if (countPage > 0) {--}}
{{--                            if (countPage >= 6) {--}}
{{--                                if (countPage >= 11) {--}}
{{--                                    if ((file_size / countPage) / 1024 > '{{$config_data_array[1000]}}' || file_size / 1024 < '{{$minfilesize}}') {--}}
{{--                                        $(obj).val('');--}}
{{--                                        alert("Please upload files between " + '{{$minfilesize}}' + "-" + '{{$config_data_array[1000]}}' + " KB. Per Page Thanks!!");--}}
{{--                                    } else {--}}
{{--                                        uploadefile(obj);--}}
{{--                                    }--}}
{{--                                } else {--}}
{{--                                    if ((file_size / countPage) / 1024 > '{{$config_data_array[10]}}' || file_size / 1024 < '{{$minfilesize}}') {--}}
{{--                                        $(obj).val('');--}}
{{--                                        alert("Please upload files between " + '{{$minfilesize}}' + "-" + '{{$config_data_array[10]}}' + " KB. Per Page Thanks!!");--}}
{{--                                    } else {--}}
{{--                                        uploadefile(obj);--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            } else if (countPage > 1) {--}}
{{--                                if ((file_size / countPage) / 1024 > '{{$config_data_array[1]}}' || file_size / 1024 < '{{$minfilesize}}') {--}}
{{--                                    alert("Please upload files between " + '{{$minfilesize}}' + "-" + '{{$config_data_array[1]}}' + " KB. Thanks!!");--}}
{{--                                    $(obj).val('');--}}
{{--                                } else {--}}
{{--                                    uploadefile(obj);--}}
{{--                                }--}}
{{--                            } else {--}}
{{--                                if (file_size / 1024 > '{{$config_data_array[1]}}' || file_size / 1024 < '{{$minfilesize}}') {--}}
{{--                                    alert("Please upload files between " + '{{$minfilesize}}' + "-" + '{{$config_data_array[1]}}' + " KB. Thanks!!");--}}
{{--                                    $(obj).val('');--}}
{{--                                } else {--}}
{{--                                    uploadefile(obj);--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }--}}
{{--                        ready = false;--}}
{{--                    } else {--}}
{{--                        setTimeout(check, 1000);--}}
{{--                    }--}}
{{--                }--}}
{{--                check();--}}
{{--            }--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
