<style>
    .approval_date_ministry{
        background-color: white;
        pointer-events: none;
    }
</style>
<div class="row">
    <div class="col-md-12 form-group {{ $errors->has('approval_memo_no_ministry') ? 'has-error' : '' }}">
        {!! Form::label('approval_memo_no_ministry', 'Approval Memo No. of Ministry:', ['class' => 'col-md-12 ']) !!}
        <div class="col-md-12 {{ $errors->has('approval_memo_no_ministry') ? 'has-error' : '' }}">
            {!! Form::text('approval_memo_no_ministry', !empty($appInfo->approval_memo_no_ministry) ? $appInfo->approval_memo_no_ministry : '', ['class' => 'form-control approval_memo_no_ministry', 'id' => 'approval_memo_no_ministry', 'placeholder' => 'Approval Memo No.', 'required' => 'required']) !!}
            {!! $errors->first('approval_memo_no_ministry', '<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group {{ $errors->has('approval_memo_no_ministry') ? 'has-error' : '' }}">
        {!! Form::label('approval_date_ministry', 'Approval Date of Ministry:', ['class' => 'col-md-12 ']) !!}
        <div class="col-md-12 {{ $errors->has('approval_date_ministry') ? 'has-error' : '' }}">
            <div class="input-group date datetimepicker" id="datepicker" data-target-input="nearest">
                {!! Form::text('approval_date_ministry', !empty($appInfo->approval_date_ministry) ? \Carbon\Carbon::parse($appInfo->approval_date_ministry)->format("d-m-Y") : '', ['class' => 'form-control approval_date_ministry', 'id' => 'approval_date_ministry', 'placeholder' => 'Approval date', 'required' => 'required']) !!}
                <div class="input-group-append"
                     data-target="#datepicker"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i
                            class="fa fa-calendar"></i></div>
                </div>
                {!! $errors->first('approval_date_ministry', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<script>
    var today = new Date();
    var yyyy = today.getFullYear();
    $('.datetimepicker').datetimepicker({
        format: 'DD-MMM-YYYY',
        maxDate: 'now',
        minDate: '01/01/' + (yyyy - 110),
        ignoreReadonly: true,
    });

    //hide calender for clicking outside of the calender.
    datePickerHide('datetimepicker');

</script>
