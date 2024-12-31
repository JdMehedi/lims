<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) die('no access right!');
?>

@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.8.1/skins/content/default/content.min.css" />
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header" style="margin-top: -1px;">
                    <h5><strong> {!!trans('messages.new_application_guideline')!!} </strong></h5>
                </div>

                {!! Form::open(array('url' => url('/settings/application_guideline/store'),'method' => 'post', 'class' => '', 'id' => 'app_guideline',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <!-- /.panel-heading -->
                <div class="card-body">

                    <div class="form-group col-md-12 row {{$errors->has('service_name') ? 'has-error' : ''}}" id="group_nm">
                        {!! Form::label('process_type_group','Process Type Group:',['class'=>'col-md-2 control-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('group_nm', $process_type, '', ['class' => 'form-control bnEng']) !!}
                            {!! $errors->first('group_nm','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 row {{$errors->has('service_name') ? 'has-error' : ''}}" id="service_name">
                        {!! Form::label('service_name','Service Name:',['class'=>'col-md-2 control-label required-star'],'required') !!}
                        <div class="col-md-6">
                            <input class="form-control" name="service_name" type="text" id="service_name" required>
                            {!! $errors->first('service_name','<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12 row">
                        <label class="control-label col-md-2  required-star">Inner Service Guideline :</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control" name="service_guideline" id="service_guideline" ref="service_guideline" required/>
                            <input type="hidden" id="page_count" name="page_count" value="0" >
                            <span class="help-block" style="margin-bottom: 0">[File Format: .pdf]</span>
                            {!! $errors->first('service_guideline','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 row">
                        <label class="control-label col-md-2  required-star">Public Service Guideline :</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control" name="service_guideline_public" id="service_guideline_public" ref="service_guideline_public" required/>
                            <input type="hidden" id="page_count_public" name="page_count_public" value="0" >
                            <span class="help-block" style="margin-bottom: 0">[File Format: .pdf]</span>
                            {!! $errors->first('service_guideline_public','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 row {{$errors->has('ordering') ? 'has-error' : ''}}">
                        {!! Form::label('ordering','Ordering:',['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('ordering', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('ordering','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group col-md-12 row">
                                {!! Form::label('active_status','Status: ',['class'=>'text-left required-star col-md-3']) !!}
                                <div class="col-md-9">
                                    <label class="radio-inline">
                                        {!! Form::radio('active_status', 1, true, ['class'=>'required']) !!}
                                        Active
                                    </label>
                                    <label class="radio-inline">
                                        {!! Form::radio('active_status', 0, false, ['class'=>'required']) !!}
                                        In-active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!-- /.box -->

                <div class="card-footer">
                    <div class="float-left">
                        <a href="{{ url('/settings/application-guideline') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                    <div class="float-right">
                        @if(ACL::getAccsessRight('settings','A'))
                            <button type="submit" class="btn btn-primary float-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                {!! Form::close() !!}<!-- /.form end -->
            </div>
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{ asset('assets\scripts\jquery.validate.js') }}"></script>
    {{--<script src="{{ asset('assets\plugins\tinymce\tinymce.min.js') }}"></script>--}}
    <script src="{{asset('assets\plugins\tinymce\tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#app_guideline").validate({
                errorPlacement: function () {
                    return true;
                },
            });
            $('#service_guideline').change(function () {
                var mime_type = this.files[0].type;

                if (mime_type != 'application/pdf') {
                    alert("Guideline must be pdf format");
                    return false;
                } else {
                    readFile(this.files[0], function (e) {
                        // use result in callback...
                        pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
                        document.getElementById('page_count').value = pagecount;
                        ready = true;

                    });

                }
            });

            $('#service_guideline_public').change(function () {
                var mime_type = this.files[0].type;

                if (mime_type != 'application/pdf') {
                    alert("Public guideline must be pdf format");
                    return false;
                } else {
                    readFile(this.files[0], function (e) {
                        // use result in callback...
                        pagecount = e.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
                        document.getElementById('page_count_public').value = pagecount;
                        ready = true;

                    });

                }
            });

        });


        tinymce.init({
            selector: '.service_details',
            plugins: 'lists',

            toolbar: 'numlist bullist  undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
            height: 250,

            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            },
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification--in');
                freeTiny.style.display = 'none';
            }
        });

        // Add table Row script
        function addTableRow(tableID, template_row_id) {

            if (tableID === 'serviceDetailsTable') {
                tinymce.remove('.service_details');
            }

            let i;
// Copy the template row (first row) of table and reset the ID and Styling
            const new_row = document.getElementById(template_row_id).cloneNode(true);
            new_row.id = "";
            new_row.style.display = "";

            // Get the total row number, and last row number of table
            let current_total_row = $('#' + tableID).find('tbody tr').length;
            let final_total_row = current_total_row + 1;


            // Generate an ID of the new Row, set the row id and append the new row into table
            let last_row_number = $('#' + tableID).find('tbody tr').last().attr('data-number');
            if (last_row_number != '' && typeof last_row_number !== "undefined") {
                last_row_number = parseInt(last_row_number) + 1;
            } else {
                last_row_number = Math.floor(Math.random() * 101);
            }

            const new_row_id = 'rowCount' + tableID + last_row_number;
            new_row.id = new_row_id;
            $("#" + tableID).append(new_row);

            // Convert the add button into remove button of the new row
            $("#" + tableID).find('#' + new_row_id).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRow("' + tableID + '","' + new_row_id + '")');

            // Icon change of the remove button of the new row
            $("#" + tableID).find('#' + new_row_id).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            // data-number attribute update of the new row
            $('#' + tableID).find('tbody tr').last().attr('data-number', last_row_number);


            // Get all select box elements from the new row, reset the selected value, and change the name of select box
            const all_select_box = $("#" + tableID).find('#' + new_row_id).find('select');
            all_select_box.val(''); // value reset
            all_select_box.prop('selectedIndex', 0);
            for (i = 0; i < all_select_box.length; i++) {
                const name_of_select_box = all_select_box[i].name;
                const updated_name_of_select_box = name_of_select_box.replace('[0]', '[' + final_total_row + ']'); //increment all array element name
                all_select_box[i].name = updated_name_of_select_box;
            }


            // Get all input box elements from the new row, reset the value, and change the name of input box
            const all_input_box = $("#" + tableID).find('#' + new_row_id).find('input');
            all_input_box.val(''); // value reset
            for (i = 0; i < all_input_box.length; i++) {
                const name_of_input_box = all_input_box[i].name;
                const updated_name_of_input_box = name_of_input_box.replace('[0]', '[' + final_total_row + ']');
                all_input_box[i].name = updated_name_of_input_box;
            }


            // Get all textarea box elements from the new row, reset the value, and change the name of textarea box
            const all_textarea_box = $("#" + tableID).find('#' + new_row_id).find('textarea');
            all_textarea_box.val(''); // value reset
            for (i = 0; i < all_textarea_box.length; i++) {
                const name_of_textarea = all_textarea_box[i].name;
                const updated_name_of_textarea = name_of_textarea.replace('[0]', '[' + final_total_row + ']');
                all_textarea_box[i].name = updated_name_of_textarea;
                $('#' + new_row_id).find('.readonlyClass').prop('readonly', true);
            }


            // var TotalRows = parseInt(rowCount) + 2;
            // var ChakingArray = [10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200];
            //
            // if(jQuery.inArray(TotalRows, ChakingArray) !== -1){
            //     $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-danger').addClass('btn-primary');
            // }else{
            //     $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            //         .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            //     $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            // }


            // Table footer adding with add more button
            if (final_total_row > 3) {
                const check_tfoot_element = $('#' + tableID + ' tfoot').length;
                if (check_tfoot_element === 0) {
                    const table_header_columns = $('#' + tableID).find('thead th');
                    let table_footer = document.getElementById(tableID).createTFoot();
                    table_footer.setAttribute('id', 'autoFooter')
                    let table_footer_row = table_footer.insertRow(0);
                    for (i = 0; i < table_header_columns.length; i++) {
                        const table_footer_th = table_footer_row.insertCell(i);
                        // if this is the last column, then push add more button
                        if (i === (table_header_columns.length - 1)) {
                            table_footer_th.innerHTML = '<a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow(\'' + tableID + '\', \'' + template_row_id + '\')"><i class="fa fa-plus"></i></a>';
                        } else {
                            table_footer_th.innerHTML = '<b>' + table_header_columns[i].innerHTML + '</b>';
                        }
                    }
                }
            }


            $("#" + tableID).find('#' + new_row_id).find('.onlyNumber').on('keydown', function (e) {
                //period decimal
                if ((e.which >= 48 && e.which <= 57)
                    //numpad decimal
                    || (e.which >= 96 && e.which <= 105)
                    // Allow: backspace, delete, tab, escape, enter and .
                    || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                    // Allow: Ctrl+A
                    || (e.keyCode == 65 && e.ctrlKey === true)
                    // Allow: Ctrl+C
                    || (e.keyCode == 67 && e.ctrlKey === true)
                    // Allow: Ctrl+V
                    || (e.keyCode == 86 && e.ctrlKey === true)
                    // Allow: Ctrl+X
                    || (e.keyCode == 88 && e.ctrlKey === true)
                    // Allow: home, end, left, right
                    || (e.keyCode >= 35 && e.keyCode <= 39)) {
                    var $this = $(this);
                    setTimeout(function () {
                        $this.val($this.val().replace(/[^0-9.]/g, ''));
                    }, 4);
                    var thisVal = $(this).val();
                    if (thisVal.indexOf(".") != -1 && e.key == '.') {
                        return false;
                    }
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error');
                    return false;
                }
            }).on('paste', function (e) {
                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^.0-9]/g, ''));
                }, 4);
            });
            tinymce.init({
                selector: '.service_details',
                plugins: 'lists',

                toolbar: 'numlist bullist  undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
                height: 250,

                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                init_instance_callback : function(editor) {
                    var freeTiny = document.querySelector('.tox .tox-notification--in');
                    freeTiny.style.display = 'none';
                },
            });

        } // end of addTableRow() function
        // Remove Table row script
        function removeTableRow(tableID, removeNum) {
            $('#' + tableID).find('#' + removeNum).remove();
            let current_total_row = $('#' + tableID).find('tbody tr').length;
            if (current_total_row <= 3) {
                const tableFooter = document.getElementById('autoFooter');
                if (tableFooter) {
                    tableFooter.remove();
                }
            }

        }
        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function () {
            $("#area-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });

        });
    </script>
@endsection <!--- footer script--->
