<style>
    .operation_exe_sql,.edit_operation_exe_sql{
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
        <span class="operationalResponseMessageDiv"></span>
        @if(ACL::getAccsessRight('settings','E'))
            <button type="button" class="float-right btn btn-success"
                    onclick="openAddOperationModal(this)"
                    data-action="{{ url('/') }}">
                <i class="fa fa-plus"></i> <b>Add New Operation</b>
            </button>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="operationList"
                   class="table table-striped table-bordered dt-responsive"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Operation name</th>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="addOperationModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Operation</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="responseMessageInModalForOperation"></div>
                <div class="form-group">
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::label('name','Operation Name',['class'=>'col-md-3 required-star','title'=>'Example: User Information','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::text('name', '', ['class' => 'form-control input-md operation_name required']) !!}
                                {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('key') ? 'has-error': ''}}">
                            {!! Form::label('key','Operation Key',['class'=>'col-md-3 required-star','title'=>'Example: user-api','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::text('key','', ['class' => 'form-control input-md operation_key required']) !!}
                                {!! $errors->first('key','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('operation_type') ? 'has-error': ''}}">
                            {!! Form::label('operation_type','Operation Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('operation_type', ['SQL'=>'SQL'],'', ['class' => 'form-control operation_type input-md required']) !!}
                                {!! $errors->first('method','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('exe_sql') ? 'has-error': ''}}">
                            {!! Form::label('exe_sql','SQL',['class'=>'col-md-3 required-star','title'=>'Example: To get user information of OSS system......','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('exe_sql', '', ['class' => 'form-control input-sm operation_exe_sql required',
                                        'size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                <button class="new_operation_sql_code_beautify_query" type="button">Beautify Query</button>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('operation_type') ? 'has-error': ''}}">
                            {!! Form::label('operation_priority','Operation Priority',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                <input type="text" name="operation_priority" class="form-control operation_priority input-md required" placeholder="Numeric value only" required onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                                {!! $errors->first('operation_priority','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md saveOperation"> <span class="spinner-icon"></span> Save</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editOperationModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Operation</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="responseMessageInModalForEditOperation"></div>
                <input type="hidden" name="operation_id" value="" id="operation_id"/>

                <div class="form-group">
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('name') ? 'has-error': ''}}">
                            {!! Form::label('name','Operation Name',['class'=>'col-md-3 required-star','title'=>'Example: User Information','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::text('name', '', ['class' => 'form-control input-md edit_operation_name required']) !!}
                                {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('key') ? 'has-error': ''}}">
                            {!! Form::label('key','Operation Key',['class'=>'col-md-3 required-star','title'=>'Example: user-api','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::text('key','', ['class' => 'form-control input-md edit_operation_key required']) !!}
                                {!! $errors->first('key','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('operation_type') ? 'has-error': ''}}">
                            {!! Form::label('operation_type','Operation Type',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                {!! Form::select('operation_type', ['SQL'=>'SQL'],'', ['class' => 'form-control edit_operation_type input-md required']) !!}
                                {!! $errors->first('method','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('exe_sql') ? 'has-error': ''}}">
                            {!! Form::label('exe_sql','SQL',['class'=>'col-md-3 required-star','title'=>'Example: To get user information of OSS system......','data-toggle'=>'tooltip']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('exe_sql', '', ['class' => 'form-control input-sm edit_operation_exe_sql required',
                                        'size'=>'5x8', 'data-charcount-enable' => 'true', "data-charcount-maxlength" => "240"]) !!}
                                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                                <button class="edit_operation_sql_code_beautify_query" type="button">Beautify Query</button>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 6px;">
                        <div class="col-md-12 {{$errors->has('operation_type') ? 'has-error': ''}}">
                            {!! Form::label('operation_priority','Operation Priority',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                <input type="text" name="operation_priority" class="form-control edit_operation_priority input-md required" placeholder="Numeric value only" required onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateOperation"> <span class="spinner-icon"></span> Update</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>


<script>

    function getOperationList(){

        $('#operationList').DataTable({
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{url("dynamic-api-engine/get-operation-list")}}',
                method: 'POST',
                data: function (d) {
                    d.api_id = $('#api_id').val();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'name', name: 'name', searchable: true},
                {data: 'key', name: 'key', searchable: true},
                {data: 'operation_type', name: 'operation_type', searchable: true},
                {data: 'priority', name: 'priority', searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    }

    function openAddOperationModal(btn) {
        $('.responseMessageInModalForOperation').empty();
        $('#addOperationModal').modal();
    }

    function storeOperationalData() {

        $('.responseMessageInModalForOperation').empty();

        var api_id = $("#api_id").val();
        var operation_name  = $(".operation_name").val();
        var operation_key = $(".operation_key").val();
        var operation_type = $(".operation_type").val();
        var operation_exe_sql = $(".operation_exe_sql").val();
        var operation_priority = $(".operation_priority").val();

        if(api_id == "" || operation_name == "" || operation_key == "" || operation_type == "" || operation_exe_sql == "" || operation_priority == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo e(url('dynamic-api-engine/store-operational-data')); ?>",
            data: {
                _token: $('input[name="_token"]').val(),
                api_id: api_id,
                operation_name: operation_name,
                operation_key: operation_key,
                operation_type: operation_type,
                operation_exe_sql: operation_exe_sql,
                operation_priority: operation_priority

            },
            success: function (response) {
                $('.spinner-icon').empty();

                if(response.responseCode == 1){
                    $(".operation_counter").html(response.total_operation);
                    $('.responseMessageInModalForOperation').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#addOperationModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#operationList').dataTable();
                    dataTable.fnDestroy();
                    getOperationList();

                }else{
                    $('.responseMessageInModalForOperation').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


    function openOperationModal(target) {

        var operation_id = target.getAttribute('data-operation_id');
        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/get-operation-data-for-edit') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                operation_id: operation_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $(".edit_operation_name").val(response.data.name);
                    $(".edit_operation_key").val(response.data.key);
                    $(".edit_operation_type").val(response.data.operation_type);
                    $(".edit_operation_exe_sql").val(window.sqlFormatter.format(response.data.exe_SQL,{
                        language:'sql',
                        uppercase:true
                    }));
                    $('#operation_id').val(response.operation_id);
                    $(".edit_operation_priority").val(response.data.priority);
                    $('#editOperationModal').modal();
                }else{

                }
            }
        });

    }


    function updateOperationalData() {

        $('.responseMessageInModalForEditOperation').empty();
        var operation_id = $("#operation_id").val();
        var operation_name  = $(".edit_operation_name").val();
        var operation_key = $(".edit_operation_key").val();
        var operation_type = $(".edit_operation_type").val();
        var operation_exe_sql = $(".edit_operation_exe_sql").val();
        var operation_priority = $(".edit_operation_priority").val();

        if(operation_id == "" || operation_name == "" || operation_key == "" || operation_type == "" || operation_exe_sql == "" || operation_priority == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo e(url('dynamic-api-engine/update-operational-data')); ?>",
            data: {
                _token: $('input[name="_token"]').val(),
                operation_id: operation_id,
                operation_name: operation_name,
                operation_key: operation_key,
                operation_type: operation_type,
                operation_exe_sql: operation_exe_sql,
                operation_priority: operation_priority

            },
            success: function (response) {
                $('.spinner-icon').empty();

                if(response.responseCode == 1){
                    $('.responseMessageInModalForEditOperation').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#editOperationModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#operationList').dataTable();
                    dataTable.fnDestroy();
                    getOperationList();

                }else{
                    $('.responseMessageInModalForEditOperation').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


    function deleteOperation(target) {

        if (!confirm('Are you sure, want to delete ?')) {
            return false;
        }
        var operation_id = target.getAttribute('data-operation_id');
        var api_id = $("#api_id").val();
        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/delete-api-operation') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                operation_id: operation_id,
                api_id: api_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $(".operation_counter").html(response.total_operation);
                    $('.operationalResponseMessageDiv').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');
                    var dataTable = $('#operationList').dataTable();
                    dataTable.fnDestroy();
                    getOperationList();

                }else{

                }
            }
        });

    }


</script>
