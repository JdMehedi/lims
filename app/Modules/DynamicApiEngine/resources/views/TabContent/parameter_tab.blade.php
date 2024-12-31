<style>
    .edited_validation_exe_sql{
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
        <span class="paramResponseMessageDiv"></span>
        @if(ACL::getAccsessRight('settings','E'))
            <button type="button" class="float-right btn btn-success"
                    onclick="addParameterModal(this)"
                    data-action="{{ url('/') }}">
                <i class="fa fa-plus"></i> <b>Add New Parameter</b>
            </button>
        @endif
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="parameterList"
                   class="table table-striped table-bordered dt-responsive"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Parameter Name</th>
                    <th>Validation Methods</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="parameterModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiInsertParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Parameter</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="responseMessageInModal"></div>

{{--                    <div class="row" style="margin-bottom: 6px;">--}}
{{--                        <div class="col-md-12 {{$errors->has('request_parameter_name') ? 'has-error': ''}}">--}}
{{--                            {!! Form::label('request_parameter_name','Parameter Name',['class'=>'col-md-3 required-star','title'=>'','data-toggle'=>'']) !!}--}}
{{--                            <div class="col-md-9">--}}
{{--                                {!! Form::text('request_parameter_name', '', ['class' => 'parameter_name form-control input-md required']) !!}--}}
{{--                                {!! $errors->first('request_parameter_name','<span class="help-block">:message</span>') !!}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="addParameter" style="margin-bottom: 6px;">
                        <div class="row">
                            <div class="col-md-12 readonlyPointer {{$errors->has('validation_method') ? 'has-error': ''}}">
                                <div class="form-group row" >
                                    {!! Form::label('request_parameter_name','Parameter Name',['class'=>'col-md-3 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('request_parameter_name[]', '', ['class' => 'parameter_name form-control input-md required','placeholder'=>'Enter parameter name']) !!}
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" data-action="insert" class="add_more_parameter_validation btn btn-success btn-sm">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md saveParameter"> <span class="spinner-icon"></span> Save</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

<!-- Validation Modal -->
<div class="modal fade" id="parameterValidationAddModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        {!! Form::open(array('url' => '/','method' => 'post','id' => 'apiUpdateParamForm','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Validation</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body validationContent">
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateValidation"> <span class="spinner-icon"></span> Update</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<!-- Parameter Edit Modal -->
<div class="modal fade" id="parameterEditModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        {!! Form::open(array('url' => '/','method' => 'post','enctype'=>'multipart/form-data',
            'method' => 'post', 'files' => true, 'role'=>'form')) !!}
        <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Update Parameter</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body parameterContent">
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="" style="cursor: pointer;" class="btn btn-info btn-md updateParameter"> <span class="spinner-icon"></span> Update</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>

    /**
     * Datatable for parameter list
     */
    function getParameterList(){

        $('#parameterList').DataTable({
            iDisplayLength: 10,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{url("dynamic-api-engine/get-parameter-list")}}',
                method: 'POST',
                data: function (d) {
                    d.api_id = $('#api_id').val();
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'parameter_name', name: 'parameter_name', searchable: true},
               {data: 'validation_method', name: 'validation_method', searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });

    }

    /**
     *
     * @param btn
     */
    function addParameterModal(btn) {
        $('.responseMessageInModal').empty();
        $('#parameterModal').modal();
    }

    /**
     *
     * Parameter Validation
     */
    function parameterValidationModal(target) {

        var param_id = target.getAttribute('data-param_id');

        target.setAttribute("disabled", "true");
//        target.closest('b').find('.spinner-section').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/get-parameter-content-for-validation') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                param_id: param_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $('.validationContent').html(response.html);
                    $('#parameterValidationAddModal').modal();
                }else{

                }
            }
        });

    }

    /**
     *
     * Parameter edit
     */

    function editParameterModal(target) {

        var param_id = target.getAttribute('data-param_id');

        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/get-parameter-content-for-edit') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                param_id: param_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                if(response.responseCode == 1){
                    $('.parameterContent').html(response.html);
                    $('#parameterEditModal').modal();
                }else{

                }
            }
        });

    }


    /**
     *
     * Parameter Delete
     */
    function deleteParameter(target) {

        if (!confirm('Are you sure, want to delete ?')) {
            return false;
        }
        var parameter_id = target.getAttribute('data-param_id');
        var api_id = $("#api_id").val();
        target.setAttribute("disabled", "true");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/delete-api-parameter') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                parameter_id: parameter_id,
                api_id: api_id
            },
            success: function (response) {
                target.removeAttribute("disabled");
                $(".param_counter").html(response.total_param);
                if(response.responseCode == 1){
                    $('.paramResponseMessageDiv').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');
                    var dataTable = $('#parameterList').dataTable();
                    dataTable.fnDestroy();
                    getParameterList();
                }else{

                }
            }
        });

    }


    /**
     *
     * @returns {boolean}
     */
    function storeParameterName() {

        $('.responseMessageInModal').empty();

        var api_id = $("#api_id").val();
        var parameter_name = $("input[name='request_parameter_name[]']").map(function(){return $(this).val();}).get();

        if( parameter_name == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/store-parameter-data') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                parameter_name: parameter_name,
                api_id: api_id

            },
            success: function (response) {
                $('.spinner-icon').empty();
                if(response.responseCode == 1){
                    $('.removeParameter ').empty();
                    $(".parameter_name").val("");
                    $(".param_counter").html(response.total_param);

                    $('.responseMessageInModal').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#parameterModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#parameterList').dataTable();
                    dataTable.fnDestroy();
                    getParameterList();

                }else{
                    $('.responseMessageInModal').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


    /**
     * Adding more parameter
     */
    function addMoreValidation(action){
        if(action == 'insert'){
            $('.addParameter').append('<div class="row"><div class="col-md-12 readonlyPointer removeParameter {{$errors->has('validation_method') ? 'has-error': ''}}" style="margin-top:6px;"><div class="form-group row">\n' +
                '                            <div class="col-md-3"></div>\n' +
                '                            <div class="col-md-8">\n' +
                '                                {!! Form::text('request_parameter_name[]', '', ['class' => 'parameter_name form-control input-md required','placeholder'=>'Enter parameter name']) !!}\n' +
                '                            </div>\n' +
                '                            <div class="col-md-1">\n' +
                '                                <button type="button" data-action="insert" class="remove_parameter_validation btn btn-danger btn-sm">-</button>\n' +
                '                            </div>\n' +
                '\n' +
                '                        </div></div></div>');
        }else{
            $('.addParameterForEdit').append('<div class="row"><div class="col-md-12 readonlyPointerEditSection removeParameterEditSection {{$errors->has('validation_method') ? 'has-error': ''}}"><div class="form-group row">\n' +
                '                            <div class="col-md-3">\n' +
                '                                {!! Form::select('edited_validation_method[]', $validationRules ,'', ['class' => 'form-control input-md required edited_validation_method']) !!}\n' +
                '                            </div>\n' +
                '                            <div class="col-md-1 validationValueSection">\n' +

                '                            </div>\n' +
                '                            <div class="col-md-6">\n' +
                '                                {!! Form::text('edited_validation_text[]', 'Validation Error !', ['class' => 'form-control input-md required','placeholder'=>'Validation message']) !!}\n' +
                '                            </div>\n' +
                '                            <div class="col-md-1">\n' +
                '                                <button type="button" data-action="update" class="remove_parameter_validation btn btn-danger btn-sm">-</button>\n' +
                '                            </div>\n' +
                '\n' +
                '                        </div></div></div>');
        }

    }


    /**
     *
     * @returns {boolean}
     */
    function updateParameterValidation() {

        $('.responseMessageInModal').empty();
        var empty = false;
        $("select[name='edited_validation_text[]']").each(function() {
            if ($(this).val() == "") {
                empty = true;
            }
        });

        var parameter_name = $(".edited_parameter_name").val();
    //    var edited_validation_exe_sql = $(".edited_validation_exe_sql").val();
    //    var edited_sql_validation_message = $(".edited_sql_validation_message").val();
        var parameter_id = $("#parameter_id").val();
        var api_id       = $("#api_id").val();

        var edited_validation_methods=[];
        $("select[name='edited_validation_method[]']").each(function(){
            edited_validation_methods.push($(this).val());
            if ($(this).val() == "") {empty = true;}
        });

        var edited_validation_text = $("input[name='edited_validation_text[]']").map(function(){return $(this).val();}).get();
        var edited_validation_method_val = $("input[name='edited_validation_method_val[]']").map(function(){return $(this).val();}).get();
        var edited_validation_exe_sql = $("textarea[name='edited_validation_exe_sql[]']").map(function(){return $(this).val();}).get();

        if(empty == true || parameter_name == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/update-parameter-validation-data') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                parameter_name: parameter_name,
                parameter_id: parameter_id,
                validation_methods: edited_validation_methods,
                validation_exe_sql: edited_validation_exe_sql,
              //  sql_validation_message: edited_sql_validation_message,
                edited_validation_method_val: edited_validation_method_val,
                api_id: api_id,
                validation_text: edited_validation_text

            },
            success: function (response) {
                $('.spinner-icon').empty();
                if(response.responseCode == 1){
                    $('.responseMessageInModal').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    $('.alert-success').fadeOut(2800);

                    setTimeout(function () {
                        $('#parameterValidationAddModal').modal('hide');
                    }, 3000);

                    var dataTable = $('#parameterList').dataTable();
                    dataTable.fnDestroy();
                    getParameterList();

                }else{
                    $('.responseMessageInModal').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


    function updateParameterName() {

        $('.responseMessageInModal').empty();
        var parameter_name = $(".edited_parameter_name").val();
        var parameter_id = $("#parameter_id").val();
        var api_id       = $("#api_id").val();

        if(parameter_id == "" || parameter_name == ""){
            alert('Please insert value in required field');
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ url('dynamic-api-engine/update-parameter-name') }}",
            data: {
                _token: $('input[name="_token"]').val(),
                parameter_name: parameter_name,
                parameter_id: parameter_id,
                api_id: api_id

            },
            success: function (response) {
                $('.spinner-icon').empty();
                if(response.responseCode == 1){
                    $('.responseMessageInParamModal').html('<div class="alert alert-success">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>');

                    setTimeout(function () {
                        $('#parameterEditModal').modal('hide');
                    }, 2000);

                    var dataTable = $('#parameterList').dataTable();
                    dataTable.fnDestroy();
                    getParameterList();

                }else{
                    $('.responseMessageInParamModal').html('<div class="alert alert-danger">\n' +
                        '  <strong>'+response.message+'</strong>\n' +
                        '</div>')
                }
            }
        });
    };


</script>
