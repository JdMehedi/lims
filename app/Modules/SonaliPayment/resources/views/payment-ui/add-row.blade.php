<div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_{{$row_id}}">
    <div class="card-header">
        Pay Order Information
        <span class="btn btn-danger cross-button" style="float: right; cursor: pointer;"  onclick="removePayOrder({{$row_id}})" >
                        <i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i>
                </span>
    </div>
    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-6 pay_order_fields pay_order_copy_preview_div" data-payrowid="{{$row_id}}" style="display: block;">
                <div class="row">
                    <label for="pay_order_copy_{{$row_id}}" class="col-md-5 text-left required-star">Pay Order Copy</label>
                    <div class="col-md-7" id="pay_order_copy_preview_{{$row_id}}">
                        <input type="file" style="border: none;" class="form-control input-md require_check required" name="pay_order_copy[{{$row_id}}]" id="pay_order_copy_{{$row_id}}" onchange="createObjUrl(event, 'pay_order_copy_url_base64_{{$row_id}}', true)" required="">
                        <input type="hidden" id="pay_order_copy_url_base64_{{$row_id}}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <label for="pay_order_number_{{$row_id}}" class="col-md-5 text-left required-star">Pay Order Number</label>
                    <div class="col-md-7">
                        <input class="form-control input-md require_check required" id="pay_order_number_{{$row_id}}" name="pay_order_number[{{$row_id}}]" type="number" value="" required="">
                    </div>
                </div>
            </div>

        </div>
        <div class="pay_order_fields" style="display: block;">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="row">
                        <label for="pay_order_date_{{$row_id}}" class="col-md-5 text-left required-star">Pay Order Date</label>
                        <div class="col-md-7" id="pay_order_date_preview">
                            <div class="input-group date datetimepicker_pay_order" id="datetimepicker_pay_order_{{$row_id}}" data-target-input="nearest">
                                <input class="form-control input-md require_check required" data-target="#datetimepicker_pay_order_{{$row_id}}" data-toggle="datetimepicker" id="pay_order_date_{{$row_id}}" placeholder="Enter pay order date" name="pay_order_date[{{$row_id}}]" type="text" value="" required="">
                                <div class="input-group-append" data-target="#datetimepicker_pay_order_{{$row_id}}" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="bank_name_{{$row_id}}" class="col-md-5 text-left required-star">Bank Name</label>
                        <div class="col-md-7">
                            {!! Form::select('bank_name[]', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check', 'id' => "bank_name_$row_id", 'onchange' => "getBranchByBankId('bank_name_$row_id',this.value, 'branch_name_$row_id')"]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-6">
                    <div class="row">
                        <label for="branch_name_{{$row_id}}" class="col-md-5 text-left required-star">Branch Name</label>
                        <div class="col-md-7">
                            <select class="form-control input-md require_check required" id="branch_name_{{$row_id}}" name="branch_name[{{$row_id}}]" required=""><option value="">Select Branch Name</option></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
