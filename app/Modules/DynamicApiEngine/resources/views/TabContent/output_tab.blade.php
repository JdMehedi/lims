<style>
    .output_exe_sql,.edit_output_exe_sql{
        background: #2B323C;
        color: #fff !important;
        padding: 20px 25px;
        border-radius: 5px;
        width: 100%;
        max-width: 100%;
        min-height: 250px;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        @if(ACL::getAccsessRight('settings','E'))
            <div class="outputResponseMessageDiv"></div>
            <button type="button" class="float-right btn btn-success"
                    onclick="openAddOutputModal(this)"
                    data-action="{{ url('/') }}">
                <i class="fa fa-plus"></i> <b>Add New Output</b>
            </button>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="outputList"
                   class="table table-striped table-bordered dt-responsive"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Output Type</th>
                    <th>Query Type</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="addOutputModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Output</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="responseMessageInModalForOutput"></div>
                <div class="form-group">
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('output_type') ? 'has-error': ''}}">
                            {!! Form::label('output_type','Output Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('output_type', ['JSON'=>'JSON'],'', ['class' => 'form-control output_type input-md required']) !!}
                                {!! $errors->first('output_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('exe_sql') ? 'has-error': ''}}">
                            {!! Form::label('output_exe_sql','SQL',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('output_exe_sql', '', ['class' => 'form-control input-sm output_exe_sql required',
                                        'size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                <button class="new_output_sql_code_beautify_query" type="button">Beautify Query</button>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('output_query_type') ? 'has-error': ''}}">
                            {!! Form::label('output_query_type','Output Query Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('output_query_type', ['1'=>'Base Query','2'=>'Child Query'],'', ['class' => 'form-control output_query_type input-md required']) !!}
                                {!! $errors->first('output_query_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md saveOutput"> <span class="spinner-icon"></span> Save</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="editOutputModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Output</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="responseMessageInModalForOutputEdit"></div>
                <input type="hidden" name="output_id" value="" id="output_id"/>
                <div class="form-group">
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('output_type') ? 'has-error': ''}}">
                            {!! Form::label('output_type','Output Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('output_type', ['JSON'=>'JSON'],'', ['class' => 'form-control edit_output_type input-md required']) !!}
                                {!! $errors->first('output_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('exe_sql') ? 'has-error': ''}}">
                            {!! Form::label('output_exe_sql','SQL',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('output_exe_sql', '', ['class' => 'form-control input-sm edit_output_exe_sql required',
                                        'size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                <button class="edit_output_sql_code_beautify_query" type="button">Beautify Query</button>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('output_query_type') ? 'has-error': ''}}">
                            {!! Form::label('output_query_type','Output Query Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('output_query_type', ['1'=>'Base Query','2'=>'Child Query'],'', ['class' => 'form-control edit_output_query_type input-md required']) !!}
                                {!! $errors->first('output_query_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateOutput"> <span class="spinner-icon"></span> Update</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>

    function getOutputList(){

        $('#outputList').DataTable({
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{url("dynamic-api-engine/get-output-list")}}',
                method: 'POST',
                data: function (d) {
                    d.api_id = $('#api_id').val();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'id', name: 'id', searchable: false},
                {data: 'output_type', name: 'output_type', searchable: true},
                {data: 'query_type', name: 'query_type', searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    }


    function openAddOutputModal(btn) {
        $('.responseMessageInModalForOutput').empty();
        $('#addOutputModal').modal();
    }


    function storeOutputData() {

        $('.responseMessageInModalForOutput').empty();

        var api_id = $("#api_id").val();
        var output_type   = $(".output_type").val();
        var output_exe_sql = $(".output_exe_sql").val();
        var output_query_type = $(".output_query_type").val();

        if(api_id == "" || output_type == "" || output_exe_sql == "" || output_query_type == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo e(url('dynamic-api-engine/store-output-data')); ?>",
            data: {
                _token: $('input[name="_token"]').val(),
                api_id: api_id,
                output_type: output_type,
                output_exe_sql: output_exe_sql,
                output_query_type: output_query_type

            },
            success: function (response) {
                $('.spinner-icon').empty();

                if(response.responseCode == 1){
                    $(".output_counter").html(response.total_output);
                    $('.responseMessageInModalForOutput').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $(".output_exe_sql").val('');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#addOutputModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#outputList').dataTable();
                    dataTable.fnDestroy();
                    getOutputList();

                }else{
                    $('.responseMessageInModalForOutput').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };

    function openOutputModal(target) {

        var output_id = target.getAttribute('data-output_id');
        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/get-output-data-for-edit') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                output_id: output_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $('.edit_output_type option[value='+response.data.output_type+']').attr('selected','selected');
                    $(".edit_output_exe_sql").val(response.data.exe_sql);
                    $('#output_id').val(response.output_id);
                    $('.edit_output_query_type option[value='+response.data.query_type+']').attr('selected','selected');
                    $('#editOutputModal').modal();
                }else{

                }
            }
        });

    }


    function updateOutputlData() {

        $('.responseMessageInModalForOutputEdit').empty();

        var api_id = $("#api_id").val();
        var output_id = $("#output_id").val();
        var output_type   = $(".edit_output_type").val();
        var output_exe_sql = $(".edit_output_exe_sql").val();
        var output_query_type = $(".edit_output_query_type").val();

        if(output_id == "" || output_type == "" || output_exe_sql == "" || output_query_type == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo e(url('dynamic-api-engine/update-output-data')); ?>",
            data: {
                _token: $('input[name="_token"]').val(),
                output_id: output_id,
                api_id: api_id,
                output_type: output_type,
                output_exe_sql: output_exe_sql,
                output_query_type: output_query_type

            },
            success: function (response) {
                $('.spinner-icon').empty();

                if(response.responseCode == 1){
                    $('.responseMessageInModalForOutputEdit').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#editOutputModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#outputList').dataTable();
                    dataTable.fnDestroy();
                    getOutputList();

                }else{
                    $('.responseMessageInModalForOutputEdit').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


    function deleteOutput(target) {

        if (!confirm('Are you sure, want to delete ?')) {
            return false;
        }
        var output_id = target.getAttribute('data-output_id');
        var api_id = $("#api_id").val();
        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/delete-api-output') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                output_id: output_id,
                api_id: api_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $(".output_counter").html(response.total_output);
                    $('.outputResponseMessageDiv').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');
                    var dataTable = $('#outputList').dataTable();
                    dataTable.fnDestroy();
                    getOutputList();

                }else{

                }
            }
        });

    }

</script>
